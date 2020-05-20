<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->default(0)->comment('圈子id');
            $table->integer('user_id')->default(0)->comment('发帖人');
            $table->string('title')->nullable()->comment('帖子标题');
            $table->text('body')->nullable()->comment('帖子内容markdown');
            $table->string('summary')->nullable()->comment('列表专用字段, 简介');
            $table->integer('likes_count')->default(0)->comment('点赞统计');
            $table->integer('comments_count')->default(0)->comment('评论统计');
            $table->integer('views_count')->default(0)->comment('阅读统计');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_posts');
    }
}
