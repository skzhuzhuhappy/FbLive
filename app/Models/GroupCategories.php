<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCategories extends Model
{
    protected $table = 'group_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort_by',
        'status',
    ];




}
