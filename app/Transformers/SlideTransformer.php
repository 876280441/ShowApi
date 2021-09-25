<?php


namespace App\Transformers;


use App\Models\Slide;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class SlideTransformer extends TransformerAbstract
{
    public function transform(Slide $slide)
    {
        return [
            'id' => $slide->id,
            'title' => $slide->title,
            'img' => $slide->img,
            'img_url' => oss_url($slide->img),
            'url' => $slide->url,
            'seq' => $slide->seq,
            'status' => $slide->status,
            'created_at' => $slide->created_at,
            'updated_at' => $slide->updated_at,
        ];
    }
}
