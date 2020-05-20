<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('类型名称');
            $table->integer('sort_by')->default(0)->comment('排序值');
            $table->tinyInteger('status')->default(0)->comment('启用状态');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_categories');
    }
}
