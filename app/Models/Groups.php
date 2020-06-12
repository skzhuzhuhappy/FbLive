<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'groups';

    protected $guarded = ['id'];

    protected $hidden = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'area_id',
        'allow_feed',
        'mode',
        'summary',
        'status',
        'feed_status',
        'img_head',
        'img_top',
        'users_count',
        'posts_count',
        'node',
        'publish_permission',
        'join_permission',
        'feed_status',
        'visible',
        'status',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    //关联 groupMember
    public function groupMembers()
    {
        return $this->hasMany(GroupMembers::class,'group_id','id');
    }



    public function recentusers($limit = 5)
    {
        return $this->users()->limit($limit);
    }

    public function category()
    {
        return $this->belongsTo(GroupCategories::class);
    }

    //更新发布动态数量
    public function updatePostCount($group_id){
        $group = Groups::find($group_id);
        $group->posts_count = $group->posts_count +1;
        $group->save();
    }
    //更新用户加入数量
    public function updateUserCount($group_id){
        $group = Groups::find($group_id);
        $group->users_count = $group->users_count +1;
        $group->save();
    }




}
