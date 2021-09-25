<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\OrderPost;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

//订单
class OrderController extends BaseController
{
    /*
     * 订单列表
     */
    public function index(Request $request)
    {
        //查询条件
        //订单号
        $order_no = $request->input('order_no');
        //支付单号
        $trade_no = $request->input('trade_no');
        //订单状态
        $status = $request->input('status');
        $orders = Order::when($order_no, function ($query) use ($order_no) {
            $query->where('order_no', $order_no);
        })->when($trade_no, function ($query) use ($trade_no) {
            $query->where('trade_no', $trade_no);
        })->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })->paginate();
        return $this->response->paginator($orders, new OrderTransformer());
    }

    /*
     * 订单详情
     */
    public function show(Order $order)
    {
        return $this->response->item($order, new OrderTransformer());
    }

    /*
     * 发货
     */
    public function post(Request $request, Order $order)
    {
        //验证提交的参数
        $request->validate([
            'express_type' => 'required|in:SF,YT,YD',
            'express_no' => 'required'
        ], [
            'express_type.required' => '快递类型必填',
            'express_type.in' => '快递类型 只能是:SF YT YD',
            'express_no.required' => '快递单号必须填写'
        ]);
        //使用事件分发
        event(new \App\Events\OrderPost($order,
            $request->input('express_type'),
            $request->input('express_no')));
        return $this->response->noContent();
    }
}
