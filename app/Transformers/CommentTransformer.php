<?php


namespace App\Transformers;


use App\Models\Category;
use App\Models\Comment;
use App\Models\Good;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    //可提供include的方法
    protected $availableIncludes = ['user', 'goods'];

    //返回数据
    public function transform(Comment $comment)
    {
        $pics_url = [];
        if (is_array($comment->pics)) {
            foreach ($comment->pics as $p) {
                array_push($pics_url, oss_url($p));
            }
        }
        return [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_id' => $comment->user_id,
            'goods_id' => $comment->goods_id,
            'rate' => $comment->rate,
            'reply' => $comment->reply,
            'pics' => $comment->pics,
            'pics_url' => $pics_url,
            'created_at' => $comment->created_at,
            'updated_at' => $comment->updated_at
        ];
    }

    /*
     * 额外的用户数据
     */
    public function includeUser(Comment $comment)
    {
        return $this->item($comment->user, new UserTransformer());
    }

    /*
    * 额外的商品数据
    */
    public function includeGoods(Comment $comment)
    {
        return $this->item($comment->goods, new GoodTransformer());
    }

}
