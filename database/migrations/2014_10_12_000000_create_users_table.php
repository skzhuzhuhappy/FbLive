<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('主键ID');
            $table->string('name',12)->comment('用户名称');
            $table->string('password',80)->comment('密码');
            $table->string('email', 150)->nullable()->default(null)->comment('user email.');
            $table->string('phone', 50)->nullable()->default(null)->comment('user phone member.');
            $table->string('introduct')->nullable()->default(null)->comment('用户简介');
            $table->tinyInteger('sex')->nullable()->default(0)->comment('用户性别');
            $table->string('location')->nullable()->default(null)->comment('用户位置');
            $table->string('avatar')->nullable()->default(null)->comment('用户头像');
            $table->string('bg')->nullable()->default(null)->comment('个人主页背景');
            $table->text('last_token')->nullable()->comment('登陆时的token');
            $table->tinyInteger('status')->default(0)->comment('用户状态 -1代表已删除 0代表正常 1代表冻结');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamps();

            $table->unique('name');
            $table->unique('email');
            $table->unique('phone');
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
        Schema::dropIfExists('users');
    }
}
