<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GroupMembersRequest;
use App\Models\GroupMembers;
use App\Models\User;
use App\Transformers\PostTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GroupMembersController extends Controller
{

    //创建
    public function store(GroupMembersRequest $request)
    {
        $result = $request->all();
        $user = Auth::user();
        if (!$user) {
            return $this->failed('未获得用户，检查token', 402);
        }
        if ($result['user_id'] != $user->getAuthIdentifier()) {
            return $this->failed('传入user_id 不是当前登陆用户', 402);
        }
        //加入
        $res = (new GroupMembers())->join_group($request->group_id, $request->user_id);
        if ($res['type'] == 'failed') {
            return $this->failed('该用户已经加入该圈子', 402);
        }
        if ($res['type'] == 'success') {
            return $this->setStatusCode($res['statusCode'])->success($res['msg']);
        }

    }

    //移除用户
    public function delete($id)
    {
        $flight = GroupMembers::find($id);
        if ($flight) {
            $flight->delete();
            return $this->setStatusCode(201)->success('移除成功');
        } else {
            return $this->failed('未查到数据', 402);
        }
    }

    //邀请用户加入
    public function userAdd(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => 'string',
            'user_id' => 'integer',
            'group_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->failed('参数名称不对', 402);
            //return $this->errorBadRequest($validator);
        }
        $where = array();
        if ($request->name) {
            $where[] = ['name', 'like', "%$request->name%"];
            $user = User::where($where)->first();
            //加入
            $res = (new GroupMembers())->join_group($request->group_id, $user->id);
        }

        if ($request->user_id) {
            $res = (new GroupMembers())->join_group($request->group_id, $request->user_id);
        }


        if ($res['type'] == 'failed') {
            return $this->failed('该用户已经加入该圈子', 402);
        }
        if ($res['type'] == 'success') {
            return $this->setStatusCode($res['statusCode'])->success($res['msg']);
        }


    }

}
