<?php

namespace App\Models;

use App\Http\Resources\Api\FeedsResource;
use App\Http\Resources\Api\GroupsResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeedLike extends Model
{
    protected $table = 'feed_like';

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

    public function feedlike()
    {
        return $this->hasMany(FeedLike::class);
    }

    //判断用户是否喜欢
    public static function is_like_auth($feed_id)
    {
        $user = Auth::user();
        if($user){
            return self::is_like_user_id($user->getAuthIdentifier(),$feed_id);
        }else{
            return false;
        }

    }

    public static function  is_like_user_id($user_id,$feed_id){
        return self::where(['user_id' => $user_id, 'feed_id' => $feed_id])->first() ? true : false;
    }

}
