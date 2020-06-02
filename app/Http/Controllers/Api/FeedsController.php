<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\FeedReplyRequest;
use App\Http\Requests\Api\FeedsRequest;
use App\Http\Resources\Api\FeedsResource;
use App\Models\Feeds;
use App\Models\Groups;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedsController extends Controller
{

    //所有圈子动态
    public function Index(Request $request)
    {
        $name = $request->name;
        if($name){
            $where[] = ['text_body', 'like', "%$name%"];
            $feed_list = Feeds::feedList($where,5);
        }else{
            $feed_list = Feeds::feedList([],5);
        }

        return FeedsResource::collection($feed_list);
    }

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
        //圈子添加动态权限
        $group = Groups::where('id',$result['group_id'])->value('feed_status');

        if($group){
            $result['status'] = 0;
        }else{
            $result['status'] = 1;
        }

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
       /* if ($result['user_id'] != $user->getAuthIdentifier()) {
            return $this->failed('传入user_id 不是当前登陆用户', 402);
        }*/
        $result['user_id'] = $user->getAuthIdentifier();
        $result['pid'] = 1;
        $result['status'] = 1;
        //创建评论
        Feeds::create($result);
        //更新评论数
        (new Feeds)->updateCommentCount($request->feed_id);

        return $this->setStatusCode(201)->success('添加动态评论成功');
    }

    //动态详情
    public function show($id)
    {
        $feeds = Feeds::findOrFail($id);
        if ($feeds->feed_id == 0) {
            return $this->success(new FeedsResource($feeds));
        } else {
            return $this->failed('请传入正确动态id', 402);
        }
    }


    //用户动态列表
    public function userIndex($id)
    {
        $feed_list = Feeds::feedList(['user_id'=>$id]);
        return FeedsResource::collection($feed_list);
    }

    //圈子下的动态列表
    public function groupIndex($id)
    {
        $feed_list = Feeds::feedList(['group_id'=>$id,'pid'=>0],2);
        //->paginate()
        return FeedsResource::collection($feed_list);
    }




}
