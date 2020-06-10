<?php

namespace App\Http\Resources\Api;

use App\Models\Enum\UserEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'sex' => $this->sex,
            'avatar' => $this->avatar,
            'bg' => $this->bg,
            'status' => UserEnum::getStatusName($this->status),
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
            //是否在小队中
            'is_group_in' =>$this->is_group_in,
            //审核加入状态：0 - 待审核、1 - 通过、2 - 拒绝
            'audit' =>$this->audit,
            //用户身份  1.加入者 2.管理者 3.创建者
            'user_type' =>$this->user_type,
        ];
    }
}
