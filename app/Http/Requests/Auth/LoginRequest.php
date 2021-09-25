<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|max:16'
        ];
    }
    public function messages()
    {
        return [
          'email.required'=>'邮箱必须填写',
          'email.email'=>'邮箱格式不正确',
          'password.required'=>'密码必须填写',
          'password.min'=>'密码不得小于6位',
          'password.max'=>'密码最大不得超过16位'
        ];
    }
}
