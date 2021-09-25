<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'city_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $city = City::find($value);
                    if (empty($city)) {
                        $fail('区域地址不存在');
                    }
                    if (!in_array($city->level, [3,4])) {
                        $fail('区域字段 必须是区或镇');
                    }
                }
            ],
            'address' => 'required',
            'phone' => 'required|regex:/^1[3-9]\d{9}$/'
        ];
    }

    /*
     * 错误提示信息
     */
    public function messages()
    {
        return [
            'name.required' => '收货人不得为空',
            'city_id.required' => '地区不得为空',
            'address.required' => '详细收货地址不得为空',
            'phone.required' => '手机号不得为空',
            'phone.regex' => '手机号格式不正确',
        ];
    }
}
