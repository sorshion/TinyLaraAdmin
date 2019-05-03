<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unicode')->nullable()->comment('unicode 字符');
            $table->string('class')->nullable()->comment('类名');
            $table->string('name')->nullable()->comment('名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
        });
        // 表注释
        DB::statement("ALTER TABLE `icons` comment '图标表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('icons');
    }
}
