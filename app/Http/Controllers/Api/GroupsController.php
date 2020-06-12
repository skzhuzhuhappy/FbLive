<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\GroupsRequest;
use App\Http\Requests\Api\GroupsUpdateRequest;
use App\Http\Resources\Api\AreasResource;
use App\Http\Resources\Api\GroupCategoriesResource;
use App\Http\Resources\Api\GroupMembersResource;
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
    public function index(Request $request)
    {
        //审核通过
        $where['status'] = 1;
        $name = $request->name;
        if ($name) {
            $where[] = ['name', 'like', "%$name%"];
        }
        //圈子列表
        $groups = Groups::where($where)->latest()->get();

        return GroupsResource::collection($groups);
    }

    //陆用户创建的所有圈子  type=ALL 全部圈子 type=ADD 创建的 type=JOIN 加入的
    public function userIndex(Request $request)
    {
        $user = Auth::user();
        if(isset($request->type) && $request->type=="ADD"){
            $where[] = ['user_id'=>$user->getAuthIdentifier()];
        }elseif(isset($request->type) && $request->type=="JOIN"){
            $group_member = GroupMembers::where(['user_id'=>$user->getAuthIdentifier()])
                ->where('user_type','!=',3)->orderBy('audit', 'asc')->latest()->get()->ToArray();
            $group_id_arr = array_column($group_member,"group_id");
            $where[] = ['in'=>['id'=>$group_id_arr]];
        }else{
            $group_member = GroupMembers::where(['user_id'=>$user->getAuthIdentifier()])
                ->where('user_type','!=',3)->orderBy('audit', 'asc')->latest()->get()->ToArray();
            $group_id_arr = array_column($group_member,"group_id");
            $where[] = ['in'=>['id'=>$group_id_arr]];
            $where[] = ['user_id'=>$user->getAuthIdentifier()];
        }
        $groups = Groups::where($where)->orderBy('status', 'asc')->latest()->get();
        return GroupsResource::collection($groups);

    }

    //用户加入的圈子列表
    public function userJoin(){

        $user = Auth::user();
        $group_member = GroupMembers::where(['user_id'=>$user->getAuthIdentifier()])
            ->where('user_type','!=',3)->orderBy('audit', 'asc')->latest()->get()->ToArray();
        $group_id_arr = array_column($group_member,"group_id");
        $where[] = ['in'=>['id'=>$group_id_arr]];
        $groups = Groups::where($where)->orderBy('status', 'asc')->latest()->get();
        //$groups = Groups::find($group_id_arr);
        return GroupsResource::collection($groups);

    }

    //用户下所有圈子(创建的和加入的)
    public function useridIndex($id)
    {
        $groups = Groups::where(['user_id' => $id])->orderBy('status', 'asc')->latest()->get();
        return GroupsResource::collection($groups);
    }

    //圈子类型 列表
    public function categorysIndex()
    {
        $categorys = GroupCategories::where(['status' => 0, 'parent_id' => 0])->orderBy('order', 'desc')->get();
        return GroupCategoriesResource::collection($categorys);
    }

    //圈子地区 列表
    public function areasIndex()
    {
        ///$area_list = Groups::select('area_id')->get()->Toarray();
        //$area_id_list = array_unique(array_column($area_list,'area_id'));
        //->whereIn('id', $area_id_list)
        $groups = Areas::where(['pid' => 0])->orderBy('created_at', 'desc')->get();
        return AreasResource::collection($groups);
    }

    //圈子下的用户列表 (审核状态列表)
    public function groupuserIndex($id)
    {
        $user_list = GroupMembers::select('user_id')->where(['group_id' => $id])->get()->ToArray();
        $groups = User::whereIn('id', $user_list)->orderBy('created_at', 'desc')->get();

        return UserResource::collection($groups);
    }


    //categoryAreaIndex
    public function cateareaIndex(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'category_id' => 'required|integer',
            'name' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->failed('参数名称不对', 402);
            //return $this->errorBadRequest($validator);
        }
        //圈子名称搜索 类型搜索
        $where['status'] = 1;
        $where['category_id'] = $request->category_id;
        if ($request->name) {
            $where[] = ['name', 'like', "%$request->name%"];
        }

        $groups = Groups::where($where)->with(['groupMembers' => function ($query) {
            //如果登陆
            $user = Auth::user();
            if ($user) {
                $query->where('user_id', $user->getAuthIdentifier());
            }
        }])->orderBy('created_at', 'desc')->get();
        //var_dump($groups);exit();
        //登陆情况
        $user = Auth::user();
        if ($user) {
            foreach ($groups as $key => $val) {
                $res = $val->groupMembers->first();
                //审核加入状态：0 - 待审核、1 - 通过、2 - 拒绝
                $groups[$key]['audit'] = $res->audit ?? "";
                //用户身份  1.加入者 2.管理者 3.创建者
                $groups[$key]['user_type'] = $res->user_type ?? "";
                $groups[$key]['is_group_in'] = $res ? true : false;
            }
        }

        return GroupsResource::collection($groups);
    }


    //返回单一圈子信息
    public function show($id)
    {
        $where['status'] = 1;
        $where['id'] = $id;

        $groups = Groups::where($where)->with(['groupMembers' => function ($query) {
            //如果登陆
            $user = Auth::user();
            if ($user) {
                $query->where('user_id', $user->getAuthIdentifier());
            }
        }])->orderBy('created_at', 'desc')->first();

        //是否可以发布动态 发布动态权限 1全部可以发布 2管理员和组员  3管理员发言
        $groups['is_publish_feed'] = $groups->publish_permission == 1 ? true : false;

        //登陆情况
        $user = Auth::user();
        if ($user) {
            $res = $groups->groupMembers->first();
            //审核加入状态：0 - 待审核、1 - 通过、2 - 拒绝
            $groups['audit'] = $res->audit ?? "";
            //用户身份  1.加入者 2.管理者 3.创建者
            $groups['user_type'] = $res->user_type ?? "";
            $groups['is_group_in'] = $res ? true : false;
            if ($res) {
                //是否可以发布动态
                if($groups->publish_permission == 2){
                    $groups['is_publish_feed'] =   $res->audit == 1 ? true : false;
                }
                if($groups->publish_permission == 3){
                    $groups['is_publish_feed'] =  $res->user_type == 3 ? true : false;
                }
            }

        }
        return $this->success(new GroupsResource($groups));
    }


    //创建圈子
    public function store(GroupsRequest $request)
    {
        //组装数据
        $result = $request->all();

        $user = Auth::user();
        if (!$user) {
            return $this->failed('未获得用户，检查token', 402);
        }

        $result['img_head'] = $this::save_base64($request->img_head);
        //var_dump($result);exit();
        $result['img_top'] = $this::save_base64($request->img_top);

        $result['user_id'] = $user->getAuthIdentifier();
        $result['status'] = 1;
        $result['users_count'] = 1;
        $group = Groups::create($result);
        if (!$group) {
            return $this->failed('圈子创建失败', 402);
        }

        //加入圈子
        $res_mem['group_id'] = $group->id;
        $res_mem['user_id'] = $group->user_id;
        $res_mem['user_type'] = 3;
        $res_mem['audit'] = 1;


        $group_mem = GroupMembers::create($res_mem);
        if (!$group_mem) {
            return $this->failed('圈子创建失败', 402);
        }
        return $this->setStatusCode(201)->success('圈子创建成功');
    }


    //更新圈子
    public function update($id, GroupsUpdateRequest $request)
    {

        $group = Groups::findOrFail($id);

        // 不属于我的圈子
        $user = Auth::user();
        if ($group->user_id != $user->getAuthIdentifier()) {
            return $this->failed('圈子用户不对应', 402);
        }
        //$group::update($request->all());
        $res_data = $request->all();
        $data = array();
        if(isset($res_data['publish_permission'])){
            $data['publish_permission'] = $res_data['publish_permission'];
        }
        if(isset($res_data['join_permission'])){
            $data['join_permission'] = $res_data['join_permission'];
        }

        if(isset($res_data['feed_status'])){
            $data['feed_status'] = $res_data['feed_status'];
        }

        if(isset($res_data['visible'])){
            $data['visible'] = $res_data['visible'];
        }

        $save = Groups::where('id', $id)->update($data);
        return $this->setStatusCode(201)->success('圈子修改成功');

    }

    //删除
    public function destroy($id)
    {

        $group = Groups::where('id', $id)->first();
        if (!$group) {
            return $this->failed('圈子用不存在', 402);
        }
        // 不属于我的圈子
        $user = Auth::user();
        if ($group->user_id != $user->getAuthIdentifier()) {
            return $this->failed('圈子用户不对应', 402);
        }

        $group->delete();

        return $this->setStatusCode(201)->success('圈子删除成功');
    }


    //用户退出圈子
    public function groupExit($id)
    {
        $user = Auth::user();
        $groupMember = GroupMembers::where(['user_id' => $user->getAuthIdentifier(), 'group_id' => $id])->first();
        if (!$groupMember) {
            return $this->failed('用户不在这个圈子', 402);
        }
        $groupMember->delete();
        return $this->setStatusCode(201)->success('用户退出圈子成功');

    }

    //检查圈子名称是否可用
    public function nameIndex(Request $request)
    {

        $validator = \Validator::make($request->input(), [
            'name' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return $this->failed('name 格式不正确', 402);
        }

        $res = Groups::where('name', $request->name)->first();
        return $this->success(['is_use' => $res ? false : true]);

    }

    //圈子下的用户列表
    public function groupMemberList($id)
    {
        $where['group_id'] = $id;
        //$where['user_type'] = 1;
        $group_member_list = GroupMembers::where($where)->with('user')->orderBy('created_at', 'desc')->get();
        //var_dump($groups);exit();
        //登陆情况
        $user = Auth::user();
        //var_dump($user);exit();
        if ($user) {
            foreach ($group_member_list as $k => $v) {
                $res = $v->user;
                //审核加入状态：0 - 待审核、1 - 通过、2 - 拒绝
                $group_member_list[$k]['name'] = $res->name ?? "";
                //用户身份  1.加入者 2.管理者 3.创建者
                $group_member_list[$k]['avatar'] = $res->avatar ?? "";
            }

        }
        // var_dump($groupmember);exit();
        return GroupMembersResource::collection($group_member_list);
    }

    //审核加入圈子者
    public function groupMemberStatus(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'audit' => 'required|Integer|between:1,2',
            'group_member_id' => 'required|Integer',
        ]);
        if ($validator->fails()) {
            return $this->failed('参数 格式不正确', 402);
        }
        $group_member = GroupMembers::find($request->group_member_id);
        if ($group_member) {
            $group_member->audit = $request->audit;
            $res = $group_member->save();
            if ($res) {
                return $this->setStatusCode(201)->success('操作成功');
            } else {
                return $this->failed('修改失败', 402);
            }
        } else {
            return $this->failed('未查到数据', 402);
        }
    }



}
