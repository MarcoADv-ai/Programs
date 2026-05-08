<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankBg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_bg', function (Blueprint $table) {
            $table->integer('char_id')->nulleable(false);
            $table->integer('top_damage')->nulleable(false);
            $table->integer('damage_done')->nulleable(false);
            $table->integer('damage_received')->nulleable(false);
            $table->integer('cq_emperium_kill')->nulleable(false);
            $table->integer('cq_barricade_kill')->nulleable(false);
            $table->integer('cq_gstone_kill')->nulleable(false);
            $table->integer('cq_wins')->nulleable(false);
            $table->integer('cq_lost')->nulleable(false);
            $table->integer('tvt_kills')->nulleable(false);
            $table->integer('tvt_deaths')->nulleable(false);
            $table->integer('tvt_wins')->nulleable(false);
            $table->integer('tvt_lost')->nulleable(false);
            $table->integer('boss_killed')->nulleable(false);
            $table->integer('tvt_tie')->nulleable(false);
            $table->integer('cs_emperium_kill')->nulleable(false);
            $table->integer('cs_barricade_kill')->nulleable(false);
            $table->integer('cs_gstone_kill')->nulleable(false);
            $table->integer('cs_wins')->nulleable(false);
            $table->integer('cs_lost')->nulleable(false);
            $table->integer('cs_tie')->nulleable(false);
            $table->integer('dte_emperium_kill')->nulleable(false);
            $table->integer('dte_wins')->nulleable(false);
            $table->integer('dte_lost')->nulleable(false);
            $table->integer('dte_tie')->nulleable(false);
            $table->integer('bz_wins')->nulleable(false);
            $table->integer('bz_lost')->nulleable(false);
            $table->integer('bz_tie')->nulleable(false);
            $table->integer('kill_count')->nulleable(false);
            $table->integer('death_count')->nulleable(false);
            $table->integer('win')->nulleable(false);
            $table->integer('lost')->nulleable(false);
            $table->integer('tie')->nulleable(false);
            $table->integer('leader_win')->nulleable(false);
            $table->integer('leader_lost')->nulleable(false);
            $table->integer('leader_tie')->nulleable(false);
            $table->integer('deserter')->nulleable(false);
            $table->integer('score')->nulleable(false);
            $table->integer('points')->nulleable(false);
            $table->integer('sp_heal_potions')->nulleable(false);
            $table->integer('hp_heal_potions')->nulleable(false);
            $table->integer('yellow_gemstones')->nulleable(false);
            $table->integer('red_gemstones')->nulleable(false);
            $table->integer('blue_gemstones')->nulleable(false);
            $table->integer('poison_bottles')->nulleable(false);
            $table->integer('acid_demostration')->nulleable(false);
            $table->integer('acid_demostration_fail')->nulleable(false);
            $table->integer('support_skills_used')->nulleable(false);
            $table->integer('healing_done')->nulleable(false);
            $table->integer('wrong_support_skills_used')->nulleable(false);
            $table->integer('wrong_healing_done')->nulleable(false);
            $table->integer('sp_used')->nulleable(false);
            $table->integer('zeny_used')->nulleable(false);
            $table->integer('spiritb_used')->nulleable(false);
            $table->integer('ammo_used')->nulleable(false);
           
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
        Schema::dropIfExists('rank_bg');
    }
}
