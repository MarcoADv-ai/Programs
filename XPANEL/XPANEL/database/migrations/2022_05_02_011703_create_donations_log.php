<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations_log', function (Blueprint $table) {
            $table->id();
            $table->string('ipn_paypal_ip');
            $table->string('master_account_id');
            $table->string('transaction_id');
            $table->string('email_player');
            $table->string('usd');
            $table->dateTime('time');
            $table->string('success');
            $table->string('log');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations_log');
    }
}
