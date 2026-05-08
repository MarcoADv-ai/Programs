<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankGuild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild_rank', function (Blueprint $table) {
            $table->integer('guild_id')->unsigned()->nulleable(false)->default(0);
            $table->integer('castle_id')->unsigned()->nulleable(false)->default(0);
            $table->integer('capture')->unsigned()->nulleable(false)->default(0);
            $table->integer('emperium')->unsigned()->nulleable(false)->default(0);
            $table->integer('treasure')->unsigned()->nulleable(false)->default(0);
            $table->integer('top_eco')->unsigned()->nulleable(false)->default(0);
            $table->integer('top_def')->unsigned()->nulleable(false)->default(0);
            $table->integer('invest_eco')->unsigned()->nulleable(false)->default(0);
            $table->integer('invest_def')->unsigned()->nulleable(false)->default(0);
            $table->integer('offensive_score')->unsigned()->nulleable(false)->default(0);
            $table->integer('defensive_score')->unsigned()->nulleable(false)->default(0);
            $table->integer('posesion_time')->unsigned()->nulleable(false)->default(0);
            $table->integer('zeny_eco')->unsigned()->nulleable(false)->default(0);
            $table->integer('zeny_def')->unsigned()->nulleable(false)->default(0);
            $table->integer('skill_battleorder')->unsigned()->nulleable(false)->default(0);
            $table->integer('skill_regeneration')->unsigned()->nulleable(false)->default(0);
            $table->integer('skill_restore')->unsigned()->nulleable(false)->default(0);
            $table->integer('skill_emergencycall')->unsigned()->nulleable(false)->default(0);
            $table->integer('off_kill')->unsigned()->nulleable(false)->default(0);
            $table->integer('off_death')->unsigned()->nulleable(false)->default(0);
            $table->integer('def_kill')->unsigned()->nulleable(false)->default(0);
            $table->integer('def_death')->unsigned()->nulleable(false)->default(0);
            $table->integer('ext_kill')->unsigned()->nulleable(false)->default(0);
            $table->integer('ext_death')->unsigned()->nulleable(false)->default(0);
            $table->integer('ali_kill')->unsigned()->nulleable(false)->default(0);
            $table->integer('ali_death')->unsigned()->nulleable(false)->default(0);
            $table->primary(['guild_id', 'castle_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guild_rank');
    }
}
