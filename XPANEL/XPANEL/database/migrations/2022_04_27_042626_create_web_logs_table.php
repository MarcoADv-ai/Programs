<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('master_account_id');
            $table->dateTime('time')->nullable(false)->default(now());
            $table->string('log_type')->nullable(false)->default('');
            $table->string('ip')->nullable(false);
            $table->string('session')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_logs');
    }
}
