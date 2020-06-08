<?php

namespace App\Models;

use App\Http\Resources\Api\FeedsResource;
use App\Http\Resources\Api\GroupsResource;
use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    protected $table = 'feeds';

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_id',
        'user_id',
        'group_id',
        'feed_id',
        'feed_content',
        'text_body',
        'status',
        'pid',
        'hot',
        'recommended_at',
        'is_comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //动态回复列表
    public static function replayList($feed_id, $status = 0)
    {
        $where['feed_id'] = $feed_id;
        $where['status'] = $status;

        $list = self::where($where)->get();
        if ($list) {
            return FeedsResource::collection($list);
        } else {
            return [];
        }
    }

    //查询动态
    public static function feedList($data, $num = 0)
    {
        //pid=0 动态 status = 1 有效
        $data['pid'] = 0;
        if ($num) {
            return self::where($data)->orderBy('hot', 'desc')->latest()->paginate($num);
        } else {
            return self::where($data)->orderBy('hot', 'desc')->latest()->get();
        }

    }

    //更新 动态点赞数
    public function updateLikeCount($feed_id){
        $feed = Feeds::find($feed_id);
        $feed->like_count = $feed->like_count +1;
        $feed->save();
    }
    //更新 动态评论数
    public function updateCommentCount($feed_id){
        $feed = Feeds::find($feed_id);
        $feed->feed_comment_count = $feed->feed_comment_count +1;
        $feed->save();
    }

    //更新 动态阅读数
    public function updatViewCount($feed_id){
        $feed = Feeds::find($feed_id);
        $feed->feed_view_count = $feed->feed_view_count +1;
        $feed->save();
    }
}
