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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
        $data['pid'] = 0;
        $data['status'] = 1;
        if ($num) {
            return self::where($data)->orderBy('created_at', 'desc')->paginate($num);
        } else {
            return self::where($data)->orderBy('created_at', 'desc')->get();
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
