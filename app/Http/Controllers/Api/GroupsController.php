<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GroupsRequest;
use App\Http\Resources\Api\GroupCategoriesResource;
use App\Http\Resources\Api\GroupsResource;
use App\Http\Resources\Api\UserResource;
use App\Models\Areas;
use App\Models\GroupCategories;
use App\Models\GroupMembers;
use App\Models\Groups;
use App\Models\User;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{

    //圈子列表
    public function index()
    {
        //圈子列表
        $groups = Groups::where('status','1')->orderBy('created_at', 'desc')->get();

        return GroupsResource::collection($groups);
    }

    //登陆用户下所有圈子
    public function userIndex()
    {
        $user = Auth::user();
        $groups = Groups::where(['user_id' => $user->getAuthIdentifier()])->orderBy('created_at', 'desc')->get();
        return GroupsResource::collection($groups);
    }
    //某个用户下所有圈子
    public function useridIndex($id)
    {
        $groups = Groups::where(['user_id' =>$id])->orderBy('created_at', 'desc')->get();
        return GroupsResource::collection($groups);
    }
    //圈子类型 列表
    public function categorysIndex()
    {

        //$category_list = Groups::select('category_id')->get()->Toarray();
        //$category_id_list = array_unique(array_column($category_list,'category_id'));
        //->whereIn('id', $category_id_list)
        $groups = GroupCategories::where(['status'=>0])->orderBy('created_at', 'desc')->get();
        return GroupCategoriesResource::collection($groups);
    }

    //圈子地区 列表
    public function areasIndex()
    {
        ///$area_list = Groups::select('area_id')->get()->Toarray();
        //$area_id_list = array_unique(array_column($area_list,'area_id'));
        //->whereIn('id', $area_id_list)
        $groups = Areas::where(['pid'=>0])->orderBy('created_at', 'desc')->get();
        return GroupCategoriesResource::collection($groups);
    }

    //圈子下的用户列表
    public function groupuserIndex($id)
    {
        $user_list = GroupMembers::select('user_id')->where(['group_id'=>$id])->get()->ToArray();
        $groups = User::whereIn('id', $user_list)->orderBy('created_at', 'desc')->get();
        return UserResource::collection($groups);
    }

    //categoryAreaIndex
    public function cateareaIndex(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'category_id' => 'required|integer',
            'area_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->failed('参数名称不对',402);
            //return $this->errorBadRequest($validator);
        }
        $groups = Groups::where(['category_id'=>$request->category_id,'area_id'=>$request->area_id])->orderBy('created_at', 'desc')->get();
        return GroupsResource::collection($groups);
    }


    //返回单一圈子信息
    public function show($id)
    {
        $groups = Groups::findOrFail($id);
        return $this->success(new GroupsResource($groups));
    }


    //创建圈子
    public function store(GroupsRequest $request)
    {
        //组装数据
        $result = $request->all();

        /*if (!$request->file('img_head')->isValid()) {
            return $this->failed('img_head 不存在',402);
        }

        if (!$request->file('img_top')->isValid()) {
            return $this->failed('img_top 不存在',402);
        }

        $img_head_path = $request->img_head->store('images');
        $img_top_path = $request->img_top->store('images');

        $result['img_head'] = $img_head_path;
        $result['img_top'] = $img_top_path;*/

        $user = Auth::user();
        if(!$user){
            return $this->failed('未获得用户，检查token',402);
        }
        $result['user_id'] = $user->getAuthIdentifier();
        $result['status'] = 1;
        $group = Groups::create($result);
        if(!$group){
            return $this->failed('圈子创建失败',402);
        }

        //加入圈子
        $res_mem['group_id'] = $group->id;
        $res_mem['group_id'] = $group->id;
        $res_mem['user_id'] = $group->user_id;
        $res_mem['user_type'] = 3;
        $group_mem = GroupMembers::create($res_mem);
        if(!$group_mem){
            return $this->failed('圈子创建失败',402);
        }
        return $this->setStatusCode(201)->success('圈子创建成功');
    }


    //更新圈子
    public function update($id, GroupsRequest $request)
    {
        $group = Groups::findOrFail($id);

        // 不属于我的圈子
        $user = Auth::user();
        if ($group->user_id != $user->getAuthIdentifier()) {
            return $this->failed('圈子用户不对应',402);
        }
        Groups::update($request->all());
        return $this->setStatusCode(201)->success('圈子修改成功');

    }
    //删除
    public function destroy($id)
    {

        $group = Groups::where('id',$id)->first();
        if(!$group){
            return $this->failed('圈子用不存在',402);
        }
        // 不属于我的圈子
        $user = Auth::user();
        if ($group->user_id != $user->getAuthIdentifier()) {
            return $this->failed('圈子用户不对应',402);
        }

        $group->delete();

        return $this->setStatusCode(201)->success('圈子删除成功');
    }


    //用户退出圈子
    public function groupExit($id){
        $user = Auth::user();
        $groupMember = GroupMembers::where(['user_id'=>$user->getAuthIdentifier(),'group_id'=>$id])->first();
        if(!$groupMember){
            return $this->failed('用户不在这个圈子',402);
        }
        $groupMember->delete();
        return $this->setStatusCode(201)->success('圈子删除成功');

    }

}
