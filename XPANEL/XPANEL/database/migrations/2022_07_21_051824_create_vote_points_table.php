<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_points', function (Blueprint $table) {
            $table->id();
            $table->integer('master_acc_id');
            $table->integer('rank_id');
            $table->string('ip');
            $table->string('mac_address');
            $table->timestamp('delay_expire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_points');
    }
}
