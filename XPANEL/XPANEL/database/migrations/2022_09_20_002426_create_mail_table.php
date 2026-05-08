<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail', function (Blueprint $table) {
            $table->id();
            $table->string('send_name')->nullable(false)->default('');
            $table->unsignedInteger('send_id')->nullable(false)->default(0);
            $table->string('dest_name')->nullable(false)->default('');
            $table->unsignedInteger('dest_id')->nullable(false)->default(0);
            $table->string('title')->nullable(false)->default('');
            $table->string('message')->nullable(false)->default('');
            $table->unsignedInteger('time')->nullable(false)->default(0);
            $table->tinyInteger('status')->nullable(false)->default(0);
            $table->unsignedInteger('zeny')->nullable(false)->default(0);
            $table->smallInteger('type')->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail');
    }
}
