<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Cart;
use App\Models\Good;
use App\Transformers\CartTransformer;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * 购物车商品列表
     */
    public function index()
    {
        $carts = Cart::where('user_id', auth('api')->id())->get();
        return $this->response->collection($carts, new CartTransformer());
    }

    /**
     * 加入购物车
     */
    public function store(Request $request)
    {
        //验证规则
        $request->validate([
            'goods_id' => 'required|exists:goods,id',
            'num' => [
                //数量不得超过商品的库存
                function ($attribute, $value, $fail) use ($request) {
                    //获取要加入购物车的商品id
                    $goods = Good::find($request->goods_id);
                    //判断当前要加入购物车的商品数量是否满足于当前商品的实际库存
                    if ($value > $goods->stock) {
                        $fail('数量不得超过库存');
                    }
                }
            ]
        ], [
            'goods_id.required' => '商品id不得为空',
            'goods_id.exists' => '不存在的商品'
        ]);
        //查询购物车数据是否存在
        $cart = Cart::where('user_id', auth('api')->id())
            ->where('goods_id', $request->input('goods_id'))
            ->first();
        //存在就更新数量
        if (!empty($cart)) {
            $cart->num = $request->input('num', 1);
            $cart->save();
            return $this->response->noContent();
        }

        Cart::create([
            'user_id' => auth('api')->id(),
            'goods_id' => $request->input('goods_id'),
            'num' => $request->input('num', 1)
        ]);
        return $this->response->created();
    }


    /**
     * 数量增加减少
     */
    public function update(Request $request, Cart $cart)
    {
        //验证规则
        $request->validate([
            'num' => [
                'required',
                'gte:1',
                function ($attribute, $value, $fail) use ($cart) {
                    if ($value > $cart->goods->stock) {
                        $fail('数量不得超过最大库存');
                    }
                }
            ]
        ], [
            'num.required' => '数量不得为空',
            'num.gte' => '数量最少是1'
        ]);
        $cart->num = $request->input('num');
        $cart->save();
        return $this->response->noContent();
    }

    /**
     * 移除购物车
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        return $this->response->noContent();
    }
}
