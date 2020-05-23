<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GroupMembersRequest;
use App\Models\GroupMembers;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Illuminate\Support\Facades\Auth;

class GroupMembersController extends Controller
{


    //创建
    public function store(GroupMembersRequest $request)
    {
        $result = $request->all();
        $user = Auth::user();
        if($result['user_id'] != $user->getAuthIdentifier()){
            return $this->failed('传入user_id 不是当前登陆用户',402);
        }

        $res = GroupMembers::where(['group_id'=>$request->group_id,'user_id'=>$request->user_id])->first();
        if($res){
            return $this->failed('该用户已经加入该圈子',402);
        }else{
            //加入圈子
            $result['user_type'] = 1;
            GroupMembers::create($result);
            $group = Groups::find($request->group_id);
            //var_dump($group);exit();
            $group->users_count = $group->users_count +1;
            $group->save();

        }
        return $this->setStatusCode(201)->success('用户加入成功');
    }


}
