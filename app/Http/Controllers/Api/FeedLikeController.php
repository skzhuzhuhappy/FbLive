<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FeedLikeRequest;
use App\Http\Requests\Api\FeedReplyRequest;
use App\Http\Requests\Api\FeedsRequest;
use App\Http\Resources\Api\FeedsResource;
use App\Http\Resources\Api\GroupsResource;
use App\Models\FeedLike;
use App\Models\Feeds;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedLikeController extends Controller
{



    //动态 点赞
    public function store(FeedLikeRequest $request)
    {
        $result = $request->all();
        $user = Auth::user();
        if (!$user) {
            return $this->failed('未获得用户，检查token', 402);
        }
        if ($result['user_id'] != $user->getAuthIdentifier()) {
            return $this->failed('传入user_id 不是当前登陆用户', 402);
        }

        FeedLike::create($result);

        return $this->setStatusCode(201)->success('动态点赞成功');
    }



}
