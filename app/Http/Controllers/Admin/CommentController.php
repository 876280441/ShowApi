<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Comment;
use App\Models\Good;
use App\Transformers\CommentTransformer;
use Dingo\Api\Http\Request;

class CommentController extends BaseController
{
    /**
     * 评价列表
     *
     */
    public function index(Request $request)
    {
        //获取搜索条件
        $rate = $request->query('rate');
        //获取商品标题
        $goods_title = $request->query('goods_title');
        $comments = Comment::when($rate, function ($query) use ($rate) {
            $query->where('rate', $rate);
        })->when($goods_title, function ($query) use ($goods_title) {
            //先查询相关的相关id
            $goods_ids = Good::where('title', 'like', "%$goods_title%")->pluck('id');
            $query->whereIn('goods_id', $goods_ids);
        })->paginate(2);
        return $this->response->paginator($comments, new CommentTransformer());
    }


    /**
     * 评价详情
     */
    public function show(Comment $comment)
    {
        return $this->response->item($comment, new CommentTransformer());
    }

    /*
     * 商家回复
     */
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'reply' => 'required|max:255'
        ], [
            'reply.required' => '回复内容不得为空',
            'reply.max' => '回复内容不得超过255字符',
        ]);
        //更新
        $comment->reply = $request->input('reply');
        $comment->save();
        return $this->response->noContent();
    }
}
