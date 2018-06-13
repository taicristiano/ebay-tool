<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDtbItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_item', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_item', 'item_height')) {
                $table->float('item_height', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'item_width')) {
                $table->float('item_width', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'item_length')) {
                $table->float('item_length', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'item_weight')) {
                $table->float('item_weight', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'pack_material_weight')) {
                $table->float('pack_material_weight', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'buy_price')) {
                $table->float('buy_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('dtb_item', 'last_mornitoring_date')) {
                $table->dateTime('last_mornitoring_date')->nullable();
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
