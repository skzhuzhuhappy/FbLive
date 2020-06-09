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
        if ($user) {
            return self::is_group_user_id($user->getAuthIdentifier(), $group_id);
        } else {
            return false;
        }
    }

    //判断用户是否在圈子中
    public static function is_group_user_id($user_id, $group_id)
    {
        return self::where(['user_id' => $user_id, 'group_id' => $group_id])->first() ? true : false;
    }

    // 用户加入圈子
    public function join_group($group_id, $user_id)
    {
        //返回数据
        $data = array();
        $res = GroupMembers::where(['group_id' => $group_id, 'user_id' => $user_id])->first();
        if ($res) {
            $data['type'] = 'failed';
            $data['statusCode'] = 402;
            $data['msg'] = '该用户已经加入该圈子';
            return $data;
        } else {
            //$join_permission= Groups::where('id',$request->group_id)->value('join_permission');
            $result['group_id'] = $group_id;
            $result['user_id'] = $user_id;
            $group = Groups::where('id', $group_id)->first();
            if ($group->join_permission == 2) {
                $result['audit'] = 0;
                $msg = '用户申请成功,等待审核';
                $statusCode = 202;
            } else {
                $result['audit'] = 1;
                $msg = '用户加入成功';
                $statusCode = 201;
            }

            if ($group->publish_permission != 1) {
                $result['can_pub'] = 1;
            }

            $result['user_type'] = 1;
            //加入圈子
            GroupMembers::create($result);
            //更新圈子加入人数
            (new Groups())->updateUserCount($group_id);

            $data['type'] = 'success';
            $data['statusCode'] = $statusCode;
            $data['msg'] = $msg;
            return $data;
        }
    }


}
