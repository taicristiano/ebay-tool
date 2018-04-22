<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDtbUserV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_user', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_user', 'name_kana')) {
                $table->string('name_kana', 50);
            }
            if (!Schema::hasColumn('dtb_user', 'ebay_account')) {
                $table->string('ebay_account', 50);
            }
            if (!Schema::hasColumn('dtb_user', 'tel')) {
                $table->string('tel', 15);
            }
            if (!Schema::hasColumn('dtb_user', 'introducer_id')) {
                $table->integer('introducer_id')->nullable();
            }
            if (!Schema::hasColumn('dtb_user', 'email')) {
                $table->string('email', 50);
            }
            if (!Schema::hasColumn('dtb_user', 'memo')) {
                $table->text('memo')->nullable();
            }
            if (!Schema::hasColumn('dtb_user', 'start_date')) {
                $table->dateTime('start_date')->nullable();
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
        //
    }
}
