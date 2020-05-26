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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function replayList($feed_id)
    {
        $list = self::where(['feed_id' => $feed_id])->get();
        if ($list) {
            return FeedsResource::collection($list);
        } else {
            return [];
        }
    }

    //查询动态
    public static function feedList($data,$num=0)
    {

        $data['feed_id'] = 0;
        if($num){
            return self::where($data)->orderBy('created_at', 'desc')->paginate($num);

        }else{
            return self::where($data)->orderBy('created_at', 'desc')->get();

        }
    }


}
