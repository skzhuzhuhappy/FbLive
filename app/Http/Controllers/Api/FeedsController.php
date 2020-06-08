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
    public function groupIndex(Request $request)
    {
        //查询条件
        $name = $request->name;
        $status = $request->status;
        $recommended_at = $request->recommended_at;
        $hot = $request->hot;
        $where = [];
        if(isset($name)){
            $where[] = ['text_body', 'like', "%$name%"];
        }
        if(isset($status)){
            $where['status'] = $status;
        }
        if(isset($recommended_at)){
            $where['recommended_at'] = $recommended_at;
        }
        if(isset($hot)){
            $where['hot'] = $hot;
        }
        $feed_list = Feeds::feedList($where);
        return FeedsResource::collection($feed_list);
    }

    //审核动态
    public function update(Request $request){
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
        $feed = Feeds::where('id',$result['feed_id'])->first();
        if(isset($result['status'])){
            $feed->status = $result['status'];
        }
        if(isset($result['recommended_at'])){
            $feed->recommended_at = $result['recommended_at'];
        }
        if(isset($result['hot'])){
            $feed->hot = $result['hot'];
        }
        $res = $feed->save();
        if($res){
            return $this->setStatusCode(201)->success('动态修改成功');
        }
    }




}
