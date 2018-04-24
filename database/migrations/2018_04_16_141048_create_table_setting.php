<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('dtb_user')->onDelete('cascade');
            $table->unsignedInteger('store_id')->default(1);
            $table->foreign('store_id')->references('id')->on('mtb_store')->onDelete('cascade');
            $table->float('paypal_fee_rate', 4, 2)->default(3.6);
            $table->float('paypal_fixed_fee', 4, 2)->default(40);
            $table->smallInteger('ex_rate_diff')->default(0);
            $table->smallInteger('gift_discount')->default(100);
            $table->smallInteger('duration')->default(30);
            $table->smallInteger('quantity')->default(1);
            $table->boolean('del_flg')->default(false);
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
        Schema::dropIfExists('dtb_setting');
    }
}
