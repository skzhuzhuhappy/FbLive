<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GroupMembersRequest;
use App\Models\GroupMembers;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Encore\Admin\Grid\Filter\Group;
use Illuminate\Support\Facades\Auth;

class GroupMembersController extends Controller
{

    //创建
    public function store(GroupMembersRequest $request)
    {
        $result = $request->all();
        $user = Auth::user();
        if(!$user){
            return $this->failed('未获得用户，检查token',402);
        }
        if($result['user_id'] != $user->getAuthIdentifier()){
            return $this->failed('传入user_id 不是当前登陆用户',402);
        }

        $res = GroupMembers::where(['group_id'=>$request->group_id,'user_id'=>$request->user_id])->first();
        if($res){
            return $this->failed('该用户已经加入该圈子',402);
        }else{
            $join_permission= Groups::where('id',$request->group_id)->value('join_permission');
            if($join_permission == 2){
                $result['audit'] = 0;
                $mes = '用户申请成功,等待审核';
                $statusCode = 202;
            }else{
                $result['audit'] = 1;
                $mes = '用户加入成功';
                $statusCode = 201;
            }
            $result['user_type'] = 1;
            //加入圈子
            GroupMembers::create($result);
            //更新圈子加入人数
            (new Groups())->updateUserCount($request->group_id);
            return $this->setStatusCode($statusCode)->success($mes);
        }
    }




}
