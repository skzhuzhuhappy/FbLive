<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class   GroupCategories extends Model
{
    protected $table = 'group_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'is_directory',
        'level',
        'path',
        'status',

    ];

    protected static function boot()
    {
        parent::boot();
        // 当创建Category时，自动初始化 path 和 level
        static::creating(function (GroupCategories $category) {
            if (is_null($category->parent_id)) { // 创建的是根目录
                $category->level = 0; // 将层级设为0
                $category->path = '-'; // 将 path 设为 -
            } else { // 创建的并非根目录
                $category->level = $category->parent->level + 1; // 将层级设为父类层级+1
                $category->path = $category->parent->path . $category->parent_id . '-'; // 将path值设为父类path+父类id
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(GroupCategories::class);
    }

    public function children()
    {
        return $this->hasMany(GroupCategories::class, 'parent_id');
    }

    /**
     * 获取所有祖先分类id
     * @date 2019-04-21
     */
    public function getPathIdsAttribute()
    {
        $path = trim($this->path, '-'); // 过滤两端的 -
        $path = explode('-', $path); // 以 - 为分隔符切割为数组
        $path = array_filter($path); // 过滤空值元素
        return $path;
    }

    /**
     * 获取所有祖先分类且按层级正序排列
     * @date 2019-04-21
     */
    public function getAncestorsAttribute()
    {
        return GroupCategories::query()
            ->whereIn('id', $this->parent_id) // 调用 getPathIdsAttribute 获取祖先类目id
            ->orderBy('level') // 按层级排列
            ->get();
    }

    /**
     * 获取所有祖先类目名称以及当前类目的名称
     * @date 2019-04-21
     */
    public function getFullNameAttribute()
    {
        return $this->ancestors // 调用 getAncestorsAttribute 获取祖先类目
        ->pluck('name') // 将所有祖先类目的 name 字段作为一个数组
        ->push($this->name) // 追加当前类目的name字段到数组末尾
        ->implode(' - '); // 用 - 符号将数组的值组装成一个字符串
    }

    /*
     * 获取 全部父类型
     * */
    public static function categoryList(){

        return self::where(['parent_id'=>0,'status'=>0])->select('id','name')->get()->toArray();
    }


}
