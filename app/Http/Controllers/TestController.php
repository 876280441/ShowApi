<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Dingo\Api\Routing\UrlGenerator;
use Dingo\Api\Auth\Auth;

class TestController extends Controller
{
    public function index()
    {
        $user = User::paginate(1);
        return $this->response->paginator($user, new UserTransformer)->setStatusCode(209)->addMeta('foo', 'bar');
    }

    public function name()
    {
        dd(app(UrlGenerator::class)->version('v1')->route('test.name'));
    }

    public function users()
    {
//        $users = User::all();
//        return $this->response->collection($users, new UserTransformer);
//        $user = app(Auth::class)->user();
//        return $user;
        $user = auth('api')->user();
        return $user;
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized();
        }
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return $this->response()->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTl() * 60
        ]);
    }

    //内部调用--分发器
    public function in()
    {
        //分发器
        $dispatcher = app('Dingo\Api\Dispatcher');
        //普通请求
        $users = $dispatcher->get('api/test');
        return $users;
    }

    //v2版本的in2
    public function in2()
    {
        $user = User::find(2);
        return $user;
    }
}
