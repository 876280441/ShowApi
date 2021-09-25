<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['user_id', 'goods_id'];
    use HasFactory;

    /*
    * 所属的商品
    */
    public function goods()
    {
        return $this->belongsTo(Good::class, 'goods_id', 'id');
    }
}
