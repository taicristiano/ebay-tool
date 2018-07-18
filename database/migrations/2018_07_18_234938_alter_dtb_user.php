<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDtbUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_user', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_user', 'monitoring_flg')) {
                $table->boolean('monitoring_flg')->default(false);
            }
            if (!Schema::hasColumn('dtb_user', 'last_monitoring_dt')) {
                $table->dateTime('last_monitoring_dt')->nullable();
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
        Schema::table('dtb_user', function (Blueprint $table) {
            //
        });
    }
}
