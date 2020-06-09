<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupMembers extends Model
{
    protected $table = 'group_members';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'user_id',
        'audit',
        'user_type',
        'disabled',
        'audit',
        'can_pub',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //判断用户当前登陆用户是否在圈子中
    public static function is_group_auth($group_id)
    {
        $user = Auth::user();
        if($user){
            return self::is_group_user_id($user->getAuthIdentifier(),$group_id);
        }else{
            return false;
        }
    }

    //判断用户是否在圈子中
    public static function is_group_user_id($user_id, $group_id)
    {
        return self::where(['user_id' => $user_id, 'group_id' => $group_id])->first() ? true : false;
    }


}
