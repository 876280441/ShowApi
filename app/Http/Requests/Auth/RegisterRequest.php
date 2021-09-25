<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:16|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名必须填写',
            'name.max' => '用户名不得大于30',
            'email.required' => '邮箱必须填写',
            'email.email' => '邮箱格式不正确',
            'email.unique' => '邮箱已存在',
            'password.required' => '密码必须填写',
            'password.min' => '密码不得小于6个字符',
            'password.max' => '密码不得大于16个字符',
            'password.confirmed' => '两次输入密码不一致',
        ];
    }
}
