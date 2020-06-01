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
        if($name) {
            $where[] = ['name', 'like', "%$name%"];
            //圈子列表
            $users = User::where($where)->orderBy('created_at', 'desc')->get();
        }else{
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

        //调用加密
        //var_dump($request->password);exit();
        $post_data['password']  = $this->http_request('http://media.fblife.com/encode/password', ['pwd'=>$request->password]);
        //调用登录
        $post_data['username'] = $request->name;
        $post_data['ip'] = $request->ip();
        $datas = $this->send_post("https://fb-cms.fblife.com/api/web/user/login", $post_data);
        ///var_dump($datas);
        if ($datas['recode'] == 200) {
            //var_dump($datas['body']['info']['icon']);exit();
            //登陆成功
            $userinfo = User::where(['name'=>$datas['body']['info']['username']])->first();
            //var_dump($userinfo);exit();
            //$userinfo = User::where(['name'=>'guaosi1'])->get();
            if(!$userinfo){
                $create = $request->all();
                $create['phone'] = $datas['body']['info']['mobile'];
                $create['avatar'] = $datas['body']['info']['icon'];
                $create['forum_user_id'] = $datas['body']['info']['uid'];
                $create['sex'] = $datas['body']['info']['type'];
                User::create($create);
                ////var_dump(11111);
            }
        }else{
            return $this->failed('账号或密码错误或不存在', 400);
        }



        $token = Auth::claims(['guard' => 'api'])->attempt(['name' =>$request->name, 'password' => $request->password]);

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
