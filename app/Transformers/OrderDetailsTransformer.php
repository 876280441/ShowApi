<?php


namespace App\Transformers;


use App\Models\OrderDetails;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class OrderDetailsTransformer extends TransformerAbstract
{
    //额外引入
    protected $availableIncludes = ['order', 'goods'];

    public function transform(OrderDetails $orderDetails)
    {
        return [
            'id' => $orderDetails->id,
            'order_id' => $orderDetails->order_id,
            'goods_id' => $orderDetails->goods_id,
            'price' => $orderDetails->price,
            'num' => $orderDetails->num,
            'created_at' => $orderDetails->created_at,
            'updated_at' => $orderDetails->updated_at,
        ];
    }

    /*
     * 额外的订单
     */
    public function includeOrder(OrderDetails $orderDetails)
    {
        return $this->item($orderDetails->order, new OrderTransformer());
    }
    /*
     * 额外的商品
     */
    public function includeGoods(OrderDetails $orderDetails)
    {
        return $this->item($orderDetails->goods, new GoodTransformer());
    }
}
