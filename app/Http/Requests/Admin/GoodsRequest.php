<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class GoodsRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required|max:255',
            'price' => 'required|min:0',
            'stock' => 'required|min:0',
            'pics' => 'required|array',
            'cover' => 'required',
            'details' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => '分类必须选择',
            'description.required' => '描述必须填写',
            'description.max' => '描述不得大于255个字符',
            'price.required' => '价格必须填写',
            'price.min' => '价格小于0?',
            'stock.required' => '库存不得为空',
            'stock.min' => '库存不得为0',
            'pics.required' => '商品小图不得小于1张',
            'cover.required' => '封面图不得为空',
            'details.required' => '商品详情必须填写'
        ];
    }
}
