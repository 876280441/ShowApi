<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//修改密码
class PasswordController extends BaseController
{
    /*
     * 修改密码
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6|max:16',
            'password' => 'required|min:6|max:16|confirmed'
        ], [
            'old_password.required' => '旧密码不得为空',
            'old_password.min' => '旧密码最少6个字符',
            'old_password.max' => '旧密码最多16个字符',
        ]);
        //获取用户输入的旧密码
        $old_password = $request->input('old_password');
        //获取数据库对象
        $user = auth('api')->user();
        //验证用户输入的旧密码与数据库的旧密码对比是否正确
        if (!password_verify($old_password, $user->password)) {
            return $this->response->errorBadRequest('旧密码不正确');
        }
        //更新用户密码
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return $this->response->noContent();
    }
}
