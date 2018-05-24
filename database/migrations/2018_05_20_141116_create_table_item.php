<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('original_id', 20);
            $table->string('item_id', 20);
            $table->smallInteger('original_type');
            $table->string('item_name', 200)->nullable();
            $table->string('category_id', 20)->nullable();
            $table->string('category_name', 200)->nullable();
            $table->string('jan_upc', 20)->nullable();
            $table->string('condition_id', 20)->nullable();
            $table->string('condition_name', 200)->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->string('duration', 10)->nullable();
            $table->smallInteger('quantity')->nullable();
            $table->unsignedInteger('shipping_policy_id')->nullable();
            $table->unsignedInteger('payment_policy_id')->nullable();
            $table->unsignedInteger('return_policy_id')->nullable();
            $table->foreign('shipping_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->foreign('payment_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->foreign('return_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->smallInteger('status')->default(0);
            $table->dateTime('day_of_sale')->nullable();
            $table->double('ship_fee', 10, 2)->nullable();
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
        Schema::dropIfExists('dtb_item');
    }
}
