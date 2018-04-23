<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDtbAuthorizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dtb_authorization', function (Blueprint $table) {
            if (Schema::hasColumn('dtb_authorization', 'category')) {
                $table->dropColumn('category');
            }

            if (!Schema::hasColumn('dtb_authorization', 'user_code')) {
                $table->string('user_code', 7);
            }
            if (!Schema::hasColumn('dtb_authorization', 'yahoo_info')) {
                $table->boolean('yahoo_info')->default(false);
            }
            if (!Schema::hasColumn('dtb_authorization', 'amazon_info')) {
                $table->boolean('amazon_info')->default(false);
            }
            if (!Schema::hasColumn('dtb_authorization', 'monitoring')) {
                $table->boolean('monitoring')->default(false);
            }
            if (!Schema::hasColumn('dtb_authorization', 'regist_limit')) {
                $table->integer('regist_limit');
            }
            if (!Schema::hasColumn('dtb_authorization', 'post_limit')) {
                $table->integer('post_limit');
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
        Schema::dropIfExists('dtb_authorization');
    }
}
