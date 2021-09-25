<?php


namespace App\Transformers;


use App\Models\Category;
use App\Models\Good;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    //返回数据
    public function transform(Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
        ];
    }

}
