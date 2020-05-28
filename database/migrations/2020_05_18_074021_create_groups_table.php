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
            $table->tinyInteger('feed_status')->unsigned()->default(0)->comment('发布的动态是否需要审核 0 不需要 1 需要');

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



