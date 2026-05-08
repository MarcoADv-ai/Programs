<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankSkillCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_skill_count', function (Blueprint $table) {
            $table->integer('char_id')->unsigned()->nullable(false);
            $table->smallInteger('id')->unsigned()->nullable(false);
            $table->integer('count')->unsigned()->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_skill_count');
    }
}
