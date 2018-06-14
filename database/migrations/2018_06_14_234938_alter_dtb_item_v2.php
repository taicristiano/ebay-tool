<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDtbItemV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_item', function (Blueprint $table) {
            if (!Schema::hasColumn('dtb_item', 'user_id')) {
                $table->unsignedInteger('user_id')->default(1);
                $table->foreign('user_id')->references('id')->on('dtb_user')->onDelete('cascade');
            }
            if (!Schema::hasColumn('dtb_item', 'keyword')) {
                $table->string('keyword', 50)->nullable();
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
