<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    /*
     * 商品详情
     */
    public function show($id)
    {
        //商品详情
        $goods = Good::where('id', '=', $id)->with([
            'comments.user' => function ($query) {
                $query->select('id', 'name', 'avatar');
            }
        ])->first()->append('pics_url');
        //相似商品
        $like_goods = Good::where('is_on', 1)
            ->select('id', 'title', 'price', 'cover', 'sales')
            ->where('category_id', $goods->category_id)
            ->inRandomOrder()//随机
            ->take(10)//取出10条
            ->get();
        //返回数据
        return $this->response->array([
            'goods' => $goods,
            'like_good' => $like_goods
        ]);
    }

    /*
     * 商品列表
     */
    public function index(Request $request)
    {
        //搜索条件
        $title = $request->query('title');//标题
        $category_id = $request->query('category_id');//分类
        //排序条件
        $sales = $request->query('sales');//销量
        $price = $request->query('price');//价格
        $comments_count = $request->query('comments_count');//评论
        //商品分页数据
        $goods = Good::select('id', 'title', 'price', 'cover', 'category_id', 'sales', 'updated_at')
            ->where('is_on', 1)
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'like', "%{$title}%");
            })//按标题搜索
            ->when($category_id, function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            })//分类搜索
            ->when($sales == 1, function ($query) use ($sales) {
                $query->orderBy('sales', 'desc');
            })//销量排序
            ->when($price == 1, function ($query) use ($price) {
                $query->orderBy('price', 'desc');
            })//价格排序
            ->withCount('comments')
            ->when($comments_count == 1, function ($query) use ($comments_count) {
                $query->orderBy('comments_count', 'desc');
            })//评论排序
            ->orderBy('updated_at', 'desc')//默认排序
            ->simplePaginate(20)
            ->appends([
                'title' => $title,
                'category_id' => $category_id,
                'sales' => $sales,
                'price' => $price,
                'comments_count' => $comments_count,
            ]);
        //推荐商品
        $recommend_goods = Good::select('id', 'title', 'price', 'cover')
            ->where('is_on', 1)
            ->withCount('comments')//统计关联的comments表有多少条评论记录
            ->inRandomOrder()//inRandomOrder是随机取数据
            ->take(10)
            ->get()->append('cover_url');
        //分类数据
        $categories = cache_category();
        return $this->response->array([
            'goods' => $goods,
            'recommend_goods' => $recommend_goods,
            'categories' => $categories
        ]);
    }
}
