<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDtbItemV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_item', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_item', 'temp_shipping_method')) {
                $table->unsignedInteger('temp_shipping_method')->default(1);
                $table->foreign('temp_shipping_method')->references('id')->on('dtb_setting_shipping')->onDelete('cascade');
            }
            if (!Schema::hasColumn('dtb_item', 'temp_profit')) {
                $table->float('temp_profit', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'max_price')) {
                $table->float('max_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'min_price')) {
                $table->float('min_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'item_des')) {
                $table->text('item_des')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dtb_item', function (Blueprint $table) {
            //
        });
    }
}
