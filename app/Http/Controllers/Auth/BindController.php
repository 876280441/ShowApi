<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendSms;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Mail\SendCode;
use Illuminate\Filesystem\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Overtrue\EasySms\EasySms;

class BindController extends BaseController
{
    public function __construct()
    {
        //使用路由中间件
        $this->middleware(['check.phone.code'])->only(['updatePhone']);
        $this->middleware(['check.email.code'])->only(['updateEmail']);
    }

    /*
     * 获取邮件验证码
     */
    public function emailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users'
        ]);
        //发送验证码到邮箱
        Mail::to($request->input('email'))->queue(new SendCode($request->input('email')));
        return $this->response->noContent();
    }

    /*
     * 更新邮箱
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'unique:users'
        ]);
        //更新用户邮箱
        $user = auth('api')->user();//获取用户对象
        $user->email = $request->input('email');//获取用户输入的邮箱
        $user->save();//保存邮箱到数据库
        return $this->response->noContent();
    }

    /*
    * 获取手机验证码
    */
    public function phoneCode(Request $request)
    {

        $request->validate([
            'phone' => 'required|regex:/^1[3-9]\d{9}$/|unique:users'
        ]);
        //发送短信事件
        SendSms::dispatch($request->input('phone'));

        return $this->response->noContent();
    }

    /*
     * 更新手机号
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'unique:users'
        ]);
        //更新用户手机号
        $user = auth('api')->user();//获取用户对象
        $user->phone = $request->input('phone');//获取用户输入的邮箱
        $user->save();//保存邮箱到数据库
        return $this->response->noContent();
    }
}
