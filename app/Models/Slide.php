<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    //返回数据中追加img_url字段
    protected $appends = ['img_url'];
    protected $fillable = ['title', 'url', 'img', 'status', 'seq'];

    /*
     * img_url 属性修改器
     */
    public function getImgUrlAttribute()
    {
        //$this->img是当前模型的属性
        return oss_url($this->img);
    }
}
