<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableShippingFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_shipping_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_id');
            $table->foreign('shipping_id')->references('id')->on('dtb_setting_shipping')->onDelete('cascade');
            $table->float('weight', 10, 2);
            $table->float('ship_fee', 11, 2);
            $table->softDeletes();
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
        Schema::dropIfExists('dtb_setting_shipping');
    }
}
