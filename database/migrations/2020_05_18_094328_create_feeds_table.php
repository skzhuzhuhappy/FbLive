<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeds', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('from_id')->unsigned()->comment('动态来源 1:pc 2:h5 3:ios 4:android 5:其他');
            $table->integer('group_id')->unsigned()->comment('圈子 ID');
            $table->integer('user_id')->unsigned()->comment('创建者 ID');
            $table->text('feed_content')->nullable()->comment('动态内容');
            $table->text('text_body')->nullable()->comment('纯文字内容');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞统计');
            $table->integer('feed_view_count')->unsigned()->default(0)->comment('阅读统计');
            $table->integer('feed_comment_count')->unsigned()->default(0)->comment('评论统计');
            $table->string('location', 100)->nullable()->comment('位置');
            $table->string('longitude', 100)->nullable()->comment('经度');
            $table->string('latitude', 100)->nullable()->comment('纬度');
            $table->string('geo_hash', 100)->nullable()->comment('地理位置范围');
            $table->tinyInteger('audit_status')->unsigned()->default(0)->comment('	审核状态, 0 - 待审核、1 - 通过、2 - 拒绝');
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feeds');
    }
}
