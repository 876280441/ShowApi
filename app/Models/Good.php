<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    use HasFactory;

    //可批量赋值的字段
    protected $fillable = ['user_id', 'title', 'category_id', 'description', 'price', 'stock', 'cover', 'pics', 'is_on', 'is_recommend', 'details'];
    /**
     * 强制转换的属性
     *
     */
    protected $casts = [
        'pics' => 'array'
    ];

    //设置追加的字段
    protected $appends = ['cover_url'];

    /*
     * oss_url修改器
     */
    public function getCoverUrlAttribute()
    {
        return oss_url($this->cover);
    }

    /*
     * pics oss_url
     */
    public function getPicsUrlAttribute()
    {
        //使用集合的方式去遍历集合
        return collect($this->pics)->map(function ($item) {
            return oss_url($item);
        });
    }

    /*
     * 商品所属分类
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /*
     * 商品所属的用户
     */
    public function user()
    {
        return $this->belongsTo('user', 'user_id', 'id');
    }

    /*
     * 商品所有的评价
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'goods_id', 'id');
    }
}
