<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDtbSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_setting', function (Blueprint $table) {
            if (Schema::hasColumn('dtb_setting', 'paypal_fee')) {
                $table->dropColumn('paypal_fee');
            }
            if (!Schema::hasColumn('dtb_setting', 'user_id')) {
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('dtb_user')->onDelete('cascade');
            }
            if (!Schema::hasColumn('dtb_setting', 'store_id')) {
                $table->unsignedInteger('store_id');
                $table->foreign('store_id')->references('id')->on('mtb_store')->onDelete('cascade');
            }
            if (!Schema::hasColumn('dtb_setting', 'paypal_fee_rate')) {
                $table->float('paypal_fee_rate', 4, 2);
            }
            if (!Schema::hasColumn('dtb_setting', 'paypal_fixed_fee')) {
                $table->float('paypal_fixed_fee', 4, 2);
            }
            if (!Schema::hasColumn('dtb_setting', 'ex_rate_diff')) {
                $table->float('ex_rate_diff', 4, 2);
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
        Schema::dropIfExists('users');
    }
}
