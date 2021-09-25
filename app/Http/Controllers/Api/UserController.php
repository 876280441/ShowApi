<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /*
     * 用户个人信息详情
     */
    public function userInfo()
    {
        return $this->response->item(auth('api')->user(), new UserTransformer());
    }

    /*
     * 更新用户信息
     */
    public function updateUserInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|max:30',
        ], [
            'name.required' => '用户名必须填写',
            'name.max' => '用户名不得大于30',
        ]);
        $user = auth('api')->user();
        $user->name = $request->input('name');
        $user->save();
        return $this->response->noContent();
    }

    /*
     * 更新用户头像
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required'
        ], [
            '必须选择头像才能上传'
        ]);
        //获取当前用户对象
        $user = auth('api')->user();
        //获取用户上传的头像
        $user->avatar = $request->input('avatar');
        $user->save();
        return $this->response->noContent();
    }
}
