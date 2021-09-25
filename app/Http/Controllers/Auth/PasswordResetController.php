<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Mail\SendCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends BaseController
{
    public function __construct()
    {
        //使用路由中间件
//        $this->middleware(['check.phone.code'])->only(['updatePhone']);
        $this->middleware(['check.email.code'])->only(['updateEmail']);
    }

    /*
    * 获取邮件验证码
    */
    public function emailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        //发送验证码到邮箱
        Mail::to($request->input('email'))->queue(new SendCode($request->input('email')));
        return $this->response->noContent();
    }

    /*
    * //提交邮箱和验证码去修改密码
    */
    public function resetPasswordByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:16|confirmed'
        ], [
            'email.required' => '邮箱不得为空',
            'email.email' => '邮箱格式不正确',
            'email.exists' => '邮箱不存在',
            'password.required' => '密码不得为空',
            'password.confirmed' => '两次密码不一致'
        ]);

        //用户用户信息
        $user = User::where('email', $request->input('email'))->first();
        //获取用户输入的密码比加密
        $user->password = bcrypt($request->input('password'));
        $user->save();//保存密码到数据库
        return $this->response->noContent();
    }
}
