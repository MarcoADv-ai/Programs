<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild', function (Blueprint $table) {
            $table->id('guild_id')->unsigned()->nullable(false);
            $table->string('name', 24)->nullable(false)->default('');
            $table->string('master', 24)->nullable(false)->default('');
            $table->tinyInteger('guild_lv')->nullable(false)->default(0);
            $table->tinyInteger('connect_member')->nullable(false)->default(0);
            $table->tinyInteger('max_member')->nullable(false)->default(0);
            $table->smallInteger('average_lv')->nullable(false)->default(0);
            $table->bigInteger('exp')->nullable(false)->default(0);
            $table->integer('next_exp')->nullable(false)->default(0);
            $table->tinyInteger('skill_point')->nullable(false)->default(0);
            $table->string('mes1', 60)->nullable(false)->default('');
            $table->string('mes2', 120)->nullable(false)->default('');
            $table->integer('emblem_len')->nullable(false)->default(0);
            $table->integer('emblem_id')->nullable(false)->default(0);
            $table->binary('emblem_data')->nullable(false)->default(null);
            $table->dateTime('last_master_change')->nullable(false)->default(null);

            $table->unique('guild_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guild');
    }
}
