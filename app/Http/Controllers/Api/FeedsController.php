<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FeedReplyRequest;
use App\Http\Requests\Api\FeedsRequest;
use App\Http\Resources\Api\FeedsResource;
use App\Http\Resources\Api\GroupsResource;
use App\Models\Feeds;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Illuminate\Support\Facades\Auth;

class FeedsController extends Controller
{


    //创建动态
    public function store(FeedsRequest $request)
    {

        $result = $request->all();
        $user = Auth::user();
        if (!$user) {
            return $this->failed('未获得用户，检查token', 402);
        }
        if ($result['user_id'] != $user->getAuthIdentifier()) {
            return $this->failed('传入user_id 不是当前登陆用户', 402);
        }
        //var_dump($result);exit();

        $result['audit_status'] = 1;
        $result['feed_id'] = 0;
        Feeds::create($result);

        (new Groups)->updatePostCount($request->group_id);

        return $this->setStatusCode(201)->success('创建动态成功');
    }

    //创建评论
    public function reply(FeedReplyRequest $request)
    {

        $result = $request->all();
        $user = Auth::user();
        if (!$user) {
            return $this->failed('未获得用户，检查token', 402);
        }

        if ($result['user_id'] != $user->getAuthIdentifier()) {
            return $this->failed('传入user_id 不是当前登陆用户', 402);
        }

        $result['audit_status'] = 1;
        Feeds::create($result);

        (new Groups)->updatePostCount($request->group_id);

        return $this->setStatusCode(201)->success('添加动态评论成功');
    }

    //动态详情
    public function show($id){
        $feeds = Feeds::findOrFail($id);
        return $this->success(new FeedsResource($feeds));
    }

    //用户动态列表
    public function userIndex($id)
    {

        $feed_list = Feeds::where(['user_id' => $id])->orderBy('created_at', 'desc')->paginate(2);

        return FeedsResource::collection($feed_list);
    }


    //圈子下的动态列表
    public function groupIndex($id)
    {
        $feed_list = Feeds::where(['group_id' => $id])->orderBy('created_at', 'desc')->paginate(2);

        return FeedsResource::collection($feed_list);
    }


}
