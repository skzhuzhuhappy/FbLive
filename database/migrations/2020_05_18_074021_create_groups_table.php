<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('小队名称');
            $table->integer('user_id')->unsigned()->comment('创建者 ID');
            $table->integer('category_id')->unsigned()->comment('分类id');
            $table->integer('area_id')->unsigned()->comment('地区id');
            $table->string('img_head')->nullable()->default(null)->comment('圈子头像');
            $table->string('img_top')->nullable()->default(null)->comment('圈子顶图');
            $table->string('location', 100)->nullable()->comment('位置');
            $table->string('longitude', 100)->nullable()->comment('经度');
            $table->string('latitude', 100)->nullable()->comment('纬度');
            $table->string('geo_hash', 100)->nullable()->comment('地理位置范围');
            $table->integer('allow_feed')->unsigned()->default(0)->comment('是否允许发布到动态');
            $table->string('mode', 100)->nullable()->default(1)->comment('1: 公开，2：私有，3：付费的');
            $table->integer('money')->unsigned()->default(0)->comment('如果 mode 为 1 用于标示收费金额');
            $table->text('summary')->nullable()->comment('简介');
            $table->integer('users_count')->unsigned()->default(0)->comment('成员数量');
            $table->integer('posts_count')->unsigned()->default(0)->comment('帖子数量');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('	审核状态, 0 -待审核、1 - 通过、2 - 拒绝');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }



}




|data |array   |圈子数据  |
|id |int   |圈子ID  |
|name |string   |圈子名称  |
|summary |string   |圈子简介  |
|img_head |string   |圈子头像图片  |
|img_top | string  |圈子顶部图片  |
|user_id |int   |用户id  |
|category_id |string   |圈子类型ID |
|area_id |int   |圈子地址ID  |
|allow_feed |int   |是否允许发布到动态 |
|mode |int   |1: 公开，2：私有，3：付费的  |
|mode_info |string   |权限中文 如：公开  |
|status |int   |审核状态, 0 - 待审核、1 - 通过、2 - 拒绝  |
|status_info |string   |状态中文  如：通过|
|created_at |string   |创建时间  |
|updated_at |string   |更新时间  |

|user_id_info |array   |用户数据  |
|id |int   |用户id  |
|name |string   |用户名称|
|email |string   |用户邮箱  |
|phone |string   |用户手机号  |
|status |int   |用户状态 -1代表已删除 0代表正常 1代表冻结  |
|created_at	 |string   |创建时间  |
|updated_at |string   |更新时间  |

|category_id_info |array   |圈子类型数据  |
|id |int   |圈子类型id  |
|name |string   |圈子类型名称|
|sort_by |int   |排序  |
|status |int   |圈子类型状态 - 0 -待审核、1 - 通过、2 - 拒绝  |
|created_at	 |string   |创建时间  |
|updated_at |string   |更新时间  |


|area_id_info |array   |地区数据  |
|id |int   |地区id  |
|name |string   |地区名称|
|pid |int   |父id用于区分 省市区 0省  |
|created_at	 |string   |创建时间  |
|updated_at |string   |更新时间  |