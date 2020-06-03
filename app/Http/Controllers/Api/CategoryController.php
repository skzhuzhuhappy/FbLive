<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CategoryRequest;
use App\Http\Requests\Api\FeedLikeRequest;
use App\Http\Requests\Api\GroupsRequest;
use App\Http\Resources\Api\GroupsResource;
use App\Models\FeedLike;
use App\Models\Feeds;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    //分类下圈子列表
    public function groupsIndex($id)
    {
        return GroupsResource::collection(Groups::where('category_id',$id)->get());
    }



}
