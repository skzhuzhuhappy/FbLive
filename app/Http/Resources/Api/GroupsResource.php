<?php

namespace App\Http\Resources\Api;

use App\Models\Areas;
use App\Models\Enum\AdminEnum;
use App\Models\Enum\CommonEnum;
use App\Models\Enum\GroupEnum;
use App\Models\FeedLike;
use App\Models\GroupCategories;
use App\Models\GroupMembers;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class GroupsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //登陆显示 是否在圈子
        if(Auth::user()){
            return [
                'id'=>$this->id,
                'name' => $this->name,
                'summary' => $this->summary,
                'img_head' => $this->img_head,
                'img_top' => $this->img_top,
                'user_id' => $this->user_id,
                'category_id' => $this->category_id,
                'area_id' => $this->area_id,
                'allow_feed' => $this->allow_feed,
                'mode' => $this->mode,
                'mode_info' => GroupEnum::getStatusName($this->mode),
                'status' => $this->status,
                'status_info' => CommonEnum::getStatusName($this->status),
                'users_count'=>$this->users_count,
                'posts_count'=>$this->posts_count,
                //发布动态权限 1全部可以发布 2管理员和组员  3管理员发言
                'publish_permission'=>$this->publish_permission,
                //加入权限 1 随意加入 2申请加入
                'join_permission'=>$this->join_permission,
                //发布的动态是否需要审核 0 不需要 1 需要
                'feed_status'=>$this->feed_status,
                //未加入是否可见 0可见 1不可见
                'visible'=>$this->visible,
                'created_at'=>(string)$this->created_at,
                'updated_at'=>(string)$this->updated_at,
                //是否在小队中
                'is_group_in' =>$this->is_group_in,
                //审核加入状态：0 - 待审核、1 - 通过、2 - 拒绝
                'audit' =>$this->audit,
                //用户身份  1.加入者 2.管理者 3.创建者
                'user_type' =>$this->user_type,
                //是否可以发布动态
                'is_publish_feed' =>$this->is_publish_feed,
                'user_id_info'=>new UserResource(User::find($this->user_id)),
                'category_id_info'=>new GroupCategoriesResource(GroupCategories::find($this->category_id)),
                'area_id_info'=>new AreasResource(Areas::find($this->area_id)),
            ];
        }else{
            return [
                'id'=>$this->id,
                'name' => $this->name,
                'summary' => $this->summary,
                'img_head' => $this->img_head,
                'img_top' => $this->img_top,
                'user_id' => $this->user_id,
                'category_id' => $this->category_id,
                'area_id' => $this->area_id,
                'allow_feed' => $this->allow_feed,
                'mode' => $this->mode,
                'mode_info' => GroupEnum::getStatusName($this->mode),
                'users_count'=>$this->users_count,
                'posts_count'=>$this->posts_count,
                //发布动态权限 1全部可以发布 2管理员和组员  3管理员发言
                'publish_permission'=>$this->publish_permission,
                //加入权限 1 随意加入 2申请加入
                'join_permission'=>$this->join_permission,
                //发布的动态是否需要审核 0 不需要 1 需要
                'feed_status'=>$this->feed_status,
                //未加入是否可见 0可见 1不可见
                'visible'=>$this->visible,
                'status' => $this->status,
                'status_info' => CommonEnum::getStatusName($this->status),
                'created_at'=>(string)$this->created_at,
                'updated_at'=>(string)$this->updated_at,
                'is_publish_feed' =>$this->is_publish_feed,
                'user_id_info'=>new UserResource(User::find($this->user_id)),
                'category_id_info'=>new GroupCategoriesResource(GroupCategories::find($this->category_id)),
                'area_id_info'=>new AreasResource(Areas::find($this->area_id)),
            ];
        }


    }
}
