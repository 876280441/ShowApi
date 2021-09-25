<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    //可批量赋值的字段
    protected $fillable = ['order_id', 'goods_id', 'price', 'num'];

    /**
     * 所属订单主表
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /*
     * 订单关联商品
     */
    public function goods()
    {
        return $this->belongsTo(Good::class, 'goods_id', 'id');
    }
}
