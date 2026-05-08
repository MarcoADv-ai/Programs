<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccRegNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acc_reg_num', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->nullable(false)->default('0');
            $table->string('key', 32)->nullable(false)->default('');
            $table->integer('index')->unsigned()->nullable(false)->default('0');
            $table->bigInteger('value')->nullable(false)->default('0');
            $table->primary(['account_id', 'key', 'index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acc_reg_num');
    }
}
