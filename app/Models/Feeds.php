<?php

namespace App\Models;

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


}
