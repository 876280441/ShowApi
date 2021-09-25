<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class SlideRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'img' => 'required',
            'url' => 'url'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '轮播图标题不得为空',
            'img.required' => '图片地址必填',
            'url.url' => 'url格式不正确'
        ];
    }
}
