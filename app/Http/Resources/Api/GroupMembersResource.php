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

class GroupMembersResource extends JsonResource
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
                'group_id' => $this->group_id,
                'user_id' => $this->user_id,
                'audit' => $this->audit,
                'user_type' => $this->user_type,
                'created_at' => $this->created_at,
                'can_pub' => $this->can_pub,
                'name' => $this->name,
                'avatar' => $this->avatar,
            ];

    }
}
