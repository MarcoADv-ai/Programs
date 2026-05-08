<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuildPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild_position', function (Blueprint $table) {
            $table->integer('guild_id')->nullable(false)->default(0);
            $table->tinyInteger('position')->unsigned()->nullable(false)->default(0);
            $table->string('name', 24)->nullable(false)->default('');
            $table->smallInteger('mode')->unsigned()->nullable(false)->default(0);
            $table->tinyInteger('exp_mode')->unsigned()->nullable(false)->default(0);
            $table->primary(['guild_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guild_position');
    }
}
