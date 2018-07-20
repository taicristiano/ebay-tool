<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDtbChangeItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_change_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('dtb_item')->onDelete('cascade');
            $table->double('original_price', 11, 2)->nullable();
            $table->double('new_price', 11, 2)->nullable();
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('dtb_change_items');
    }
}
