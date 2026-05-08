<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('char', function (Blueprint $table) {
            $table->increments('char_id')->startingValue(150000)->unsigned()->nullable(false);
            $table->integer('account_id')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('char_num')->nullable(false)->default('0');
            $table->string('name')->nullable(false)->default('');
            $table->smallInteger('class')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('base_level')->unsigned()->nullable(false)->default('1');
            $table->smallInteger('job_level')->unsigned()->nullable(false)->default('1');
            $table->bigInteger('base_exp')->unsigned()->nullable(false)->default('0');
            $table->bigInteger('job_exp')->unsigned()->nullable(false)->default('0');
            $table->integer('zeny')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('str')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('agi')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('vit')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('int')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('dex')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('luk')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('pow')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('sta')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('wis')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('spl')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('con')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('crt')->unsigned()->nullable(false)->default('0');
            $table->integer('max_hp')->unsigned()->nullable(false)->default('0');
            $table->integer('hp')->unsigned()->nullable(false)->default('0');
            $table->integer('max_sp')->unsigned()->nullable(false)->default('0');
            $table->integer('sp')->unsigned()->nullable(false)->default('0');
            $table->integer('max_ap')->unsigned()->nullable(false)->default('0');
            $table->integer('ap')->unsigned()->nullable(false)->default('0');
            $table->integer('status_point')->unsigned()->nullable(false)->default('0');
            $table->integer('skill_point')->unsigned()->nullable(false)->default('0');
            $table->integer('trait_point')->unsigned()->nullable(false)->default('0');
            $table->integer('option')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('karma')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('manner')->unsigned()->nullable(false)->default('0');
            $table->integer('party_id')->unsigned()->nullable(false)->default('0');
            $table->integer('guild_id')->unsigned()->nullable(false)->default('0');
            $table->integer('pet_id')->unsigned()->nullable(false)->default('0');
            $table->integer('homun_id')->unsigned()->nullable(false)->default('0');
            $table->integer('elemental_id')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('hair')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('hair_color')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('clothes_color')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('body')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('weapon')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('shield')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('head_top')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('head_mid')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('head_bottom')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('robe')->unsigned()->nullable(false)->default('0');
            $table->string('last_map')->nullable(false)->default('');
            $table->smallInteger('last_x')->unsigned()->nullable(false)->default('53');
            $table->smallInteger('last_y')->unsigned()->nullable(false)->default('111');
            $table->string('save_map')->nullable(false)->default('');
            $table->smallInteger('save_x')->unsigned()->nullable(false)->default('53');
            $table->smallInteger('save_y')->unsigned()->nullable(false)->default('111');
            $table->integer('partner_id')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('online')->nullable(false)->default('0');
            $table->integer('father')->unsigned()->nullable(false)->default('0');
            $table->integer('mother')->unsigned()->nullable(false)->default('0');
            $table->integer('child')->unsigned()->nullable(false)->default('0');
            $table->integer('fame')->unsigned()->nullable(false)->default('0');
            $table->smallInteger('rename')->unsigned()->nullable(false)->default('0');
            $table->integer('delete_date')->unsigned()->nullable(false)->default('0');
            $table->integer('moves')->unsigned()->nullable(false)->default('0');
            $table->integer('unban_time')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('font')->unsigned()->nullable(false)->default('0');
            $table->integer('uniqueitem_counter')->unsigned()->nullable(false)->default('0');
            $table->enum('sex', ['M', 'F'])->nullable(false);
            $table->tinyInteger('hotkey_rowshift')->unsigned()->nullable(false)->default(0);
            $table->tinyInteger('hotkey_rowshift2')->unsigned()->nullable(false)->default(0);
            $table->integer('clan_id')->unsigned()->nullable(false)->default('0');
            $table->date('last_login')->nullable(true)->default(null);
            $table->integer('title_id')->unsigned()->nullable(false)->default('0');
            $table->tinyInteger('show_equip')->nullable(false)->default('0');
            $table->bigInteger('playtime', false, true)->nullable(false)->default('0');

            $table->tinyInteger('inventory_slots')->nullable(false)->default('100');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('char');
    }
}
