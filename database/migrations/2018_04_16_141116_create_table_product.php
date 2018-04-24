<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('original_id');
            $table->integer('product_id');
            $table->smallInteger('original_type');
            $table->string('product_name', 200)->nullable();
            $table->string('product_image', 200)->nullable();
            $table->double('price', 10, 2)->nullable();
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
        Schema::dropIfExists('dtb_product');
    }
}
