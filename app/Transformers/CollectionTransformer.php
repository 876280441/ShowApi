<?php


namespace App\Transformers;


use App\Models\Collection;
use App\Models\Good;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CollectionTransformer extends TransformerAbstract
{

    public function transform(Collection $collection)
    {
        return [
            'id' => $collection->id,
            'user_id' => $collection->user_id,
            'goods_id' => $collection->goods_id,
            'created_at' => $collection->created_at,
            'updated_at' => $collection->updated_at,
        ];
    }

}
