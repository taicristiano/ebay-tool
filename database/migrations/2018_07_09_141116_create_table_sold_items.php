<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSoldItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_sold_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('dtb_item')->onDelete('cascade');
            $table->string('order_id', 50)->nullable();
            $table->string('order_line_id', 50)->default(1);
            $table->string('ebay_item_id', 50)->nullable();
            $table->smallInteger('auto_buy_flg')->default(0);
            $table->string('buyer_postal_code', 10)->nullable();
            $table->string('buyer_email', 50)->nullable();
            $table->string('buyer_static_alias', 50)->nullable();
            $table->string('buyer_user_id', 12)->nullable();
            $table->double('sold_price', 11, 2)->nullable();
            $table->string('transaction_id', 12)->nullable();
            $table->integer('sold_quantity')->nullable();
            $table->smallInteger('order_status')->default(1);
            $table->dateTime('paid_time')->nullable();
            $table->double('ship_cost', 11, 2)->nullable();
            $table->dateTime('order_date')->nullable();
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
        Schema::dropIfExists('dtb_sold_items');
    }
}
