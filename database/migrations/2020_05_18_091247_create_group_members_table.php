<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->default(0)->comment('圈子id');
            $table->integer('user_id')->default(0)->comment('用户');
            $table->tinyInteger('audit')->default(0)->comment('审核状态：0 - 待审核、1 - 通过、2 - 拒绝');
            $table->tinyInteger('user_type')->nullable()->default('1')->comment(' 1.加入者 2.管理者 3.创建者');
            $table->tinyInteger('disabled')->default(0)->comment('是否禁用 0 不禁用 1 禁用');

            $table->timestamps();
            $table->softDeletes();

            $table->index('group_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_members');
    }
}
