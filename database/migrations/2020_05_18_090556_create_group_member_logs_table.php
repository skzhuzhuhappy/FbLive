<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMemberLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_member_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->default(0)->comment('圈子id');
            $table->integer('user_id')->default(0)->comment('用户');
            $table->integer('member_id')->default(0)->comment('圈子成员ID');
            $table->tinyInteger('status')->default(0)->comment('审核状态：0 - 待审核、1 - 通过、2 - 拒绝');
            $table->integer('auditer')->default(0)->comment('审核人');
            $table->timestamps();
            $table->softDeletes();

            $table->index('group_id');
            $table->index('user_id');
            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_member_logs');
    }
}
