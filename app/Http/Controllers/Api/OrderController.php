<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Good;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Facades\Express\Facade\Express;

class OrderController extends BaseController
{
    /*
     * 预览订单
     */
    public function preview()
    {
        //地址数据
        $address = Address::where('user_id', auth('api')->id())
            ->orderBy('is_default', 'desc')
            ->get();
        //购物车数据
        $carts = Cart::where('user_id', '=', auth('api')->id())
            ->where('is_checked', 1)
            ->with('goods:id,title,price,cover')
            ->get();
        //返回数据
        return $this->response->array([
            'carts' => $carts,
            'address' => $address
        ]);
    }

    /*
     * 提交订单
     */
    public function store(Request $request)
    {

        $request->validate([
            'address_id' => 'required|exists:addresses,id'
        ], [
            'address_id.required' => '收货地址，不得为空'
        ]);
        //处理插入的数据
        $user_id = auth('api')->id();//用户id
        //订单号
        $order_on = date('YmdHis') . mt_rand(100000, 999999);
        //订单总价
        $amount = 0;
        //获取购物车当前选中的数据
        $carts = Cart::where('user_id', $user_id)
            ->where('is_checked', 1)
            ->with('goods:id,price,stock,title')
            ->get();
        //要插入的订单详情的数据
        $insertData = [];
        //计算总价
        foreach ($carts as $key => $cart) {
            //如果有商品库存不足，提示用户重新选择
            if ($cart->goods->stock < $cart->num) {
                return $this->response->errorBadRequest($cart->goods->title . '库存不足,请重新选择商品');
            }
            //将订单的数据储存到一个数组中
            $insertData[] = [
                'goods_id' => $cart->goods_id,
                'price' => $cart->goods->price,
                'num' => $cart->num
            ];
            $amount += $cart->goods->price * $cart->num;
        }
        try {
            //开启事务
            DB::beginTransaction();
            //生成订单
            $order = Order::create([
                'user_id' => $user_id,
                'order_no' => $order_on,
                'amount' => $amount,
                'address_id' => $request->input('address_id')
            ]);
            //生成订单详情
            $order->orderDetails()->createMany($insertData);
            //删除已结算的购物车数据
            Cart::where('user_id', $user_id)
                ->where('is_checked', 1)
                ->delete();
            //减去商品的库存量
            foreach ($carts as $cart) {
                Good::where('id', $cart->goods_id)->decrement('stock', $cart->num);
            }
            //无异常就提交数据
            DB::commit();
            return $this->response->created();
        } catch (\Exception $e) {
            //事务回滚
            DB::rollBack();
            throw $e;
        }

    }

    /*
     * 订单详情
     */
    public function show(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }

    /*
     * 订单列表
     */
    public function index(Request $request)
    {
        //查询不同状态的订单
        $status = $request->query('status');
        //根据标题
        $title = $request->query('title');
        $orders = Order::where('user_id', auth('api')->id())
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($title, function ($query) use ($title) {
                //因为订单里面没有title，但是他的关联表就有
                $query->whereHas('goods', function ($query) use ($title) {
                    $query->where('title', 'like', "%{$title}%");
                });
            })
            ->paginate(3);
        return $this->response->paginator($orders, new OrderTransformer());
    }

    /*
     * 物流查询
     */
    public function express(Order $order)
    {
        //如果不是处于已发货状态或订单已完成状态，物流是不可以差的
        if (!in_array($order->status, [3, 4])) {
            return $this->response->errorBadRequest('订单状态异常，物流不得查询');
        }
        $result = Express::track($order->express_type, $order->express_no);
        if (!is_array($result)) {
            return $this->response->errorBadRequest($result);
        }
        return $this->response->array($result);
    }

    /*
     * 确认收货
     */
    public function confirm(Order $order)
    {
        if ($order->status == 4) {
            return $this->response->errorBadRequest('订单已收货 不得重复确认');
        }
        if ($order->status == 5) {
            return $this->response->errorBadRequest('订单完成 不得重复确认');
        }
        if ($order->status != 3) {
            return $this->response->errorBadRequest('订单状态异常或商家未发货故不得确认收货');
        }
        try {
            DB::beginTransaction();//开启事务
            //将订单状态修改为已收货
            $order->status = 4;
            $order->save();
            //一次性获取订单详情
            $orderDetails = $order->orderDetails;
            //收货后，增加订单下所有的商品销量
            foreach ($orderDetails as $orderDetail) {
                //更新商品销量
                Good::where('id', $orderDetail->goods_id)->increment('sales', $orderDetail->num);
            }

            DB::commit();//提交数据
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        //将订单状态修改为已收货
        $order->status = 4;
        $order->save();
        //收货后，增加订单下所有的商品销量
        return $this->response->noContent();
    }

}
