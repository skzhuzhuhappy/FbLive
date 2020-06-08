<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\Api\UserResource;
use App\Jobs\Api\SaveLastTokenJob;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class UserController extends Controller
{

    //返回用户列表
    public function index(Request $request)
    {
        $name = $request->name;
        if ($name) {
            $where[] = ['name', 'like', "%$name%"];
            //圈子列表
            $users = User::where($where)->orderBy('created_at', 'desc')->get();
        } else {
            $users = User::paginate(3);

        }
        //3个用户为一页
        return UserResource::collection($users);
    }

    //返回单一用户信息
    public function show(User $user)
    {
        return $this->success(new UserResource($user));
    }

    //返回当前登录用户信息
    public function info()
    {
        $user = Auth::user();
        return $this->success(new UserResource($user));
    }

    //用户注册
    public function store(UserRequest $request)
    {
        User::create($request->all());
        return $this->setStatusCode(201)->success('用户注册成功');
    }

    //用户登录
    public function login(Request $request)
    {
        $name = trim($request->name);
        $pwd = trim($request->password);

        $res = $this->getBbsUser($name, $pwd, 2, 'http://media.fblife.com/encode/login');
        //ar_dump($res);exit();
        if (!empty($res)) {
            //登陆成功
            $userinfo = User::where(['name' => $res['name']])->first();
            if (!$userinfo) {
                $res['password'] = $pwd;
                User::create($res);
            }
        } else {
            return $this->failed('账号或密码错误或不存在', 400);
        }

        $token = Auth::claims(['guard' => 'api'])->attempt(['name' => $res['name'], 'password' => $pwd]);

        //$token = Auth::claims(['guard' => 'api'])->attempt(['name' =>$request->name, 'password' => $request->password]);

        if ($token) {
            //如果登陆，先检查原先是否有存token，有的话先失效，然后再存入最新的token
            $user = Auth::user();
            if ($user->last_token) {
                try {
                    Auth::setToken($user->last_token)->invalidate();
                } catch (TokenExpiredException $e) {
                    //因为让一个过期的token再失效，会抛出异常，所以我们捕捉异常，不需要做任何处理
                }
            }
            SaveLastTokenJob::dispatch($user, $token);
            $token_time = time() + config('jwt.ttl');

            return $this->setStatusCode(201)->success(['token' => 'bearer ' . $token,
                'token_end_time' => $token_time,
                'user_id' => $user->getAuthIdentifier(),
            ]);

        }

        return $this->failed('账号或密码错误或不存在', 400);

    }

    //用户退出
    public function logout()
    {
        Auth::logout();
        return $this->success('退出成功...');
    }
}
