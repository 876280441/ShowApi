<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    //用户登录
    public function login(LoginRequest $request)
    {
        //获取用户输入的email和密码
        $credentials = request(['email', 'password']);
        //如果token为空就是没有通过验证
        if (!$token = auth('api')->attempt($credentials)) {
            //返回一个认证未通过的错误
            return $this->response->errorUnauthorized();
        }
        //检查用户状态
        $user = auth('api')->user();
        if ($user->is_locked == 1) {
            return $this->response->errorForbidden('用户被锁定');
        }
        //将$token格式化再返回
        return $this->respondWithToken($token);
    }

    /*
     * 格式化返回
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,//token
            'token_type' => 'Bearer',//token类型
            //过期时间
            'expires_in' => auth('api')->factory()->getTTL() * 6000
        ]);
    }

    /*
     * 退出登录
     */
    public function logout()
    {
        //退出登录
        auth('api')->logout();
        return response()->noContent();
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
}
