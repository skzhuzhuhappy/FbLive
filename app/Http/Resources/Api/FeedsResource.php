<?php

namespace App\Http\Resources\Api;

use App\Models\Enum\UserEnum;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedsResource extends JsonResource
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
            'from_id' => $this->from_id,
            'group_id' => $this->group_id,
            'user_id' =>$this->user_id,
            'feed_content' => $this->feed_content,
            'text_body' => $this->text_body,
            'like_count' => $this->like_count,
            'feed_view_count' => $this->feed_view_count,
            'feed_comment_count' => $this->feed_comment_count,
            'audit_status' => $this->audit_status,
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
            'group_id_info'=>new GroupsResource(Groups::find($this->group_id)),
            'user_id_info'=> new UserResource(User::find($this->user_id)),
        ];
    }
}