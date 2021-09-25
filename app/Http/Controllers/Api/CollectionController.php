<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Good;
use App\Transformers\CollectionTransformer;
use App\Transformers\GoodTransformer;
use Illuminate\Http\Request;

class CollectionController extends BaseController
{
    /*
     * 商品的收藏与取消
     */
    public function store(Request $request, Good $good)
    {
        $result = Collection::where('user_id', auth('api')->id())
            ->where('goods_id', $good->id);
        //获取当前收藏商品数据
        $data = $result->get();
        if (!$result->exists()) {
            Collection::create([
                'goods_id' => $good->id,
                'user_id' => auth('api')->id()
            ]);
            return $this->response->created();
        } else {
            Collection::destroy($data);
            return $this->response->noContent();
        }
    }

    /*
     * 收藏商品列表
     */
    public function index()
    {
        $data = Collection::with('goods:id,price,title,description,cover')->where('user_id', auth('api')->id())->get();
        return json_decode($data);
    }
}
