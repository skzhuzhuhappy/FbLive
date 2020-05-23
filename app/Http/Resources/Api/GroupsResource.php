<?php

namespace App\Http\Resources\Api;

use App\Models\Areas;
use App\Models\Enum\AdminEnum;
use App\Models\Enum\CommonEnum;
use App\Models\Enum\GroupEnum;
use App\Models\GroupCategories;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at'=>(string)$this->created_at,
            'updated_at'=>(string)$this->updated_at,
            'user_id_info'=>new UserResource(User::find($this->user_id)),
            'category_id_info'=>new GroupCategoriesResource(GroupCategories::find($this->category_id)),
            'area_id_info'=>new AreasResource(Areas::find($this->area_id)),
        ];
    }
}
