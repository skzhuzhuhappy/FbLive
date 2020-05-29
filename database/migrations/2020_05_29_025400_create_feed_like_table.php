<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_like', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_id')->unsigned()->comment('动态id');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('是否有效 0有效 1无效');
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
        Schema::dropIfExists('feed_like');
    }
}
