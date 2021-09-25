<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    /*
     * 评论
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'goods_id' => 'required',
            'content' => 'required'
        ], [
            'goods_id.required' => '商品id 不能为空',
            'content.required' => '评论内容 不能为空'
        ]);
        //只有确认收货才可以评论 status = 4
        if ($order->status != 4) {
            return $this->response->errorBadRequest('只有确认收货后才能评论');
        }
        //要评论的商品必须是这个订单里面的，由于订单细节里面包含了商品id，使用可以从细节表里面去取商品
        if (!in_array($request->input('goods_id'), $order->orderDetails()->pluck('goods_id')->toArray())) {
            return $this->response->errorBadRequest('此订单不包含该商品');
        }
        //已经评论过了的不能重复评论
        $checkComment = Comment::where('user_id', auth('api')->id())
            ->where('order_id', $order->id)
            ->where('goods_id', $request->input('goods_id'))
            ->count();
        //如果查询出当前用户id一样和订单id一样并且商品id一样的数据，就说明已经评论过了
        if ($checkComment > 0) {
            return $this->response->errorBadRequest('此商品已评论过，不得再次评论');
        }
        //生成评论数据
        $request->offsetSet('user_id', auth('api')->id());//用户id
        $request->offsetSet('order_id', $order->id);//订单id
        Comment::create($request->all());
        //评论成功后，将订单状态修改为已完成 status==5
        $order->status = 5;
        $order->save();
        return $this->response->created();
    }
}
