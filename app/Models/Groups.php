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
        'img_head',
        'img_top'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function recentusers($limit = 5)
    {
        return $this->users()->limit($limit);
    }

    public function category()
    {
        return $this->belongsTo(GroupCategories::class);
    }


}
