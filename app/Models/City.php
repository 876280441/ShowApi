<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'city';

    /*
     * 自己的子类
     */
    public function children()
    {
        return $this->hasMany(City::class, 'parentid', 'id');
    }
    /*
     * 父级
     */
    public function parent()
    {
        return $this->belongsTo(City::class, 'parentid', 'id');
    }
}
