<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDtbSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_setting', 'paypal_email')) {
                $table->string('paypal_email', 40)->nullable();
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
        Schema::table('dtb_setting', function (Blueprint $table) {
            //
        });
    }
}
