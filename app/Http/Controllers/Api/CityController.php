<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    /*
     * 省市区数据
     */
    public function index(Request $request)
    {
        //获取省市区数据，从缓存中获取
        $reData = city_cache($request->query('level', 1));
        return $reData;
    }
}
