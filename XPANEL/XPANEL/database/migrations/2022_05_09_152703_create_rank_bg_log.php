<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankBgLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_bg_log', function (Blueprint $table) {
            $table->id()->nullable(false);
            $table->dateTime('time')->nullable(false);
            $table->string('killer', 25)->nullable(false);
            $table->integer('killer_id')->nullable(false);
            $table->string('killed', 25)->nullable(false);
            $table->integer('killed_id')->nullable(false);
            $table->string('map', 11)->nullable(false)->default('');
            $table->integer('skill')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_bg_log');
    }
}
