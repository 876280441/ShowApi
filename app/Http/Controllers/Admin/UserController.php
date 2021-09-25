<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Transformers\UserTransformer;
use Dingo\Api\Http\Request;

class UserController extends BaseController
{
    /**
     * 用户列表
     *
     */
    public function index(Request $request)
    {
        //获取用户输入的值
        $name = $request->input('name');
        $email = $request->input('email');
        //然后有获取到用户输入的值就进入搜索匹配
        $users = User::when($name, function ($query) use ($name) {
            //用户匹配采用的是模糊匹配
            $query->where('name', 'like', "%$name%");
        })->when($email, function ($query) use ($email) {
            //邮箱搜索采用的是完整匹配
            $query->where('email', $email);
        })->paginate(2);
        return $this->response->paginator($users, new UserTransformer());
    }


    /**
     * 用户详情
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    /*
     * 禁用和启用用户
     */
    public function lock(User $user)
    {
        $user->is_locked = $user->is_locked == 0 ? 1 : 0;
        $user->save();
        return $this->response->noContent();
    }

}
