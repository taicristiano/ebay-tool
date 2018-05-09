<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtbCategoryFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtb_category_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_id', 10);
            $table->string('category_path', 1024);
            $table->float('standard_fee_rate', 4, 2);
            $table->float('basic_fee_rate', 4, 2);
            $table->float('premium_fee_rate', 4, 2);
            $table->float('anchor_fee_rate', 4, 2);
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
        Schema::dropIfExists('mtb_category_fee');
    }
}
