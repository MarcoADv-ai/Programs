<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankWoe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_woe', function (Blueprint $table) {
            $table->integer('char_id')->nullable(false)->default(0);
            $table->date('date')->nullable(false);
            $table->integer('guild_id')->nullable(false)->default(0);
            $table->integer('kill_count')->nullable(false)->default(0);
            $table->integer('death_count')->nullable(false)->default(0);
            $table->integer('score')->nullable(false)->default(0);
            $table->integer('top_damage')->nullable(false)->default(0);
            $table->integer('damage_done')->nullable(false)->default(0);
            $table->integer('damage_received')->nullable(false)->default(0);
            $table->integer('emperium_damage')->nullable(false)->default(0);
            $table->integer('guardian_damage')->nullable(false)->default(0);
            $table->integer('barricade_damage')->nullable(false)->default(0);
            $table->integer('gstone_damage')->nullable(false)->default(0);
            $table->integer('emperium_kill')->nullable(false)->default(0);
            $table->integer('guardian_kill')->nullable(false)->default(0);
            $table->integer('barricade_kill')->nullable(false)->default(0);
            $table->integer('gstone_kill')->nullable(false)->default(0);
            $table->integer('sp_heal_potions')->nullable(false)->default(0);
            $table->integer('hp_heal_potions')->nullable(false)->default(0);
            $table->integer('yellow_gemstones')->nullable(false)->default(0);
            $table->integer('red_gemstones')->nullable(false)->default(0);
            $table->integer('blue_gemstones')->nullable(false)->default(0);
            $table->integer('poison_bottles')->nullable(false)->default(0);
            $table->integer('acid_demostration')->nullable(false)->default(0);
            $table->integer('acid_demostration_fail')->nullable(false)->default(0);
            $table->integer('support_skills_used')->nullable(false)->default(0);
            $table->integer('healing_done')->nullable(false)->default(0);
            $table->integer('wrong_support_skills_used')->nullable(false)->default(0);
            $table->integer('wrong_healing_done')->nullable(false)->default(0);
            $table->integer('sp_used')->nullable(false)->default(0);
            $table->integer('zeny_used')->nullable(false)->default(0);
            $table->integer('spiritb_used')->nullable(false)->default(0);
            $table->integer('ammo_used')->nullable(false)->default(0);
            $table->primary('char_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_woe');
    }
}
