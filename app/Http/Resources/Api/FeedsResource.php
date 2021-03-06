<?php

namespace App\Http\Resources\Api;

use App\Models\Enum\CommonEnum;
use App\Models\Enum\UserEnum;
use App\Models\FeedLike;
use App\Models\Feeds;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->feed_id) && $this->feed_id == 0) {
            return [
                'id' => $this->id,
                'from_id' => $this->from_id,
                'group_id' => $this->group_id,
                'user_id' => $this->user_id,
                'feed_id' => $this->feed_id,
                'feed_content' => explode(',', $this->feed_content),
                'text_body' => $this->text_body,
                'like_count' => $this->like_count,
                'feed_view_count' => $this->feed_view_count,
                'feed_comment_count' => $this->feed_comment_count,
                'status' => $this->status,
                'status_info' => CommonEnum::getStatusName($this->status),
                'created_at' => (string)$this->created_at,
                'updated_at' => (string)$this->updated_at,
                'recommended_at' => $this->recommended_at,
                'hot' => $this->hot,
                'updated_at' => (string)$this->updated_at,
                'group_id_info' => new GroupsResource(Groups::find($this->group_id)),
                'user_id_info' => new UserResource(User::find($this->user_id)),
                //'feed_id_info' => new FeedsResource(Feeds::find($this->feed_id)),
                'feed_id_info' =>(new Feeds())->replayList($this->id,1),
                'is_like' =>FeedLike::is_like_auth($this->id),

            ];
        } else {
            return [
                'id' => $this->id,
                'from_id' => $this->from_id,
                'group_id' => $this->group_id,
                'user_id' => $this->user_id,
                'feed_id' => $this->feed_id,
                'feed_content' => explode(',', $this->feed_content),
                'text_body' => $this->text_body,
                'like_count' => $this->like_count,
                'feed_view_count' => $this->feed_view_count,
                'feed_comment_count' => $this->feed_comment_count,
                'status' => $this->status,
                'status_info' => CommonEnum::getStatusName($this->status),
                'created_at' => (string)$this->created_at,
                'updated_at' => (string)$this->updated_at,
                'group_id_info' => new GroupsResource(Groups::find($this->group_id)),
                'user_id_info' => new UserResource(User::find($this->user_id)),
                'is_like' =>FeedLike::is_like_auth($this->id),

            ];
        }
    }
}
