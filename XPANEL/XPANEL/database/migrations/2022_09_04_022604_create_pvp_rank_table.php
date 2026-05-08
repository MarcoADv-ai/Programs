<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvpRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pvp_rank', function (Blueprint $table) {
            $table->integer('char_id')->unsigned()->nullable(false);
            $table->integer('kill')->unsigned()->nullable(false)->default(0);
            $table->integer('dead')->unsigned()->nullable(false)->default(0);
            $table->integer('damage_done')->unsigned()->nullable(false)->default(0);
            $table->integer('damage_received')->unsigned()->nullable(false)->default(0);
            $table->integer('elo')->unsigned()->nullable(false)->default(0);
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
        Schema::dropIfExists('pvp_rank');
    }
}
