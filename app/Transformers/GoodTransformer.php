<?php


namespace App\Transformers;


use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class GoodTransformer extends TransformerAbstract
{
    //定义可用的关联，就是对外可访问的
    protected $availableIncludes = ['category', 'user', 'comments'];

    //返回数据
    public function transform(Good $good)
    {
        $pics_url = [];
        foreach ($good->pics as $p) {
            array_push($pics_url, oss_url($p));
        }
        return [
            'id' => $good->id,
            'title' => $good->title,
            'category_id' => $good->category_id,
            'description' => $good->description,
            'price' => $good->price,
            'stock' => $good->stock,
            'pics' => $good->pics,
            'pics_url' => $pics_url,
            'cover' => $good->cover,
            'cover_url' => oss_url($good->cover),
            'details' => $good->details,
            'is_on' => $good->is_on,
            'is_recommend' => $good->is_recommend,
            'created_at' => $good->created_at,
            'updated_at' => $good->updated_at
        ];
    }
    /*
     * 额外的分类数据
     */
    //返回的时候包含这个返回
    public function includeCategory(Good $good)
    {
        return $this->item($good->category, new CategoryTransformer());
    }
    /*
     * 返回额外的用户数据
     */
    //返回的时候包含这个返回
    public function includeUser(Good $good)
    {
        return $this->item($good->user, new UserTransformer());
    }
    /*
     * 返回额外的评价数据
     */
    //返回的时候包含这个返回
    public function includeComments(Good $good)
    {
        return $this->collection($good->comments, new CommentTransformer());
    }
}
