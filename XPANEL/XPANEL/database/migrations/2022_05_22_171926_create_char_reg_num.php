<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharRegNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('char_reg_num', function (Blueprint $table) {
            $table->integer('char_id')->nullable(false)->default(0);
            $table->string('key', 32)->nullable(false)->default('');
            $table->integer('index')->nullable(false)->default(0);
            $table->integer('value')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('char_reg_num');
    }
}
