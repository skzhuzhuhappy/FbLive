<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge', function (Blueprint $table) {
            $table->increments('id');

            // 作者、分类
            $table->unsignedInteger('author_id')->index();
            $table->unsignedInteger('category_id')->index();

            // 封面、标题、副标题、简介
            $table->string('cover', 128);
            $table->string('title', 64);
            $table->string('subtitle', 64)->nullable();
            $table->string('intro')->nullable();

            // 价格 + 积分
            $table->unsignedInteger('price');
            $table->unsignedInteger('score');

            // 是否启用章节结构
            $table->unsignedTinyInteger('chapters_enabled');

            // chapters_enabled=false时，免费内容、收费内容
            $table->text('free_content')->nullable();
            $table->text('paid_content')->nullable();

            // 一些统计字段
            $table->unsignedInteger('views_count');
            $table->unsignedInteger('likes_count');
            $table->unsignedInteger('orders_count');
            $table->unsignedInteger('comments_count');
            $table->unsignedSmallInteger('chapters_count');
            $table->unsignedSmallInteger('chapters_pub_count');
            $table->unsignedSmallInteger('chapters_unpub_count');

            // 全局置顶、作者置顶、分类置顶
            $table->timestamp('topped_at')->nullable();
            $table->timestamp('author_topped_at')->nullable();
            $table->timestamp('category_topped_at')->nullable();

            // 状态 0未发布 1已发布 2已下架
            $table->unsignedTinyInteger('status');

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
        Schema::dropIfExists('knowledge');
    }
}
