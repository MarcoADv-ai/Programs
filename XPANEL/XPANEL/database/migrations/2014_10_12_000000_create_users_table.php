<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('country')->default('ES');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('donation_points')->default(0);
            $table->integer('vote_points')->default(0);
            $table->integer('special_pack')->default(0);
            $table->integer('deluxe_pack')->default(0);
            $table->integer('epic_pack')->default(0);
            $table->integer('legendary_pack')->default(0);
            $table->integer('status')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
