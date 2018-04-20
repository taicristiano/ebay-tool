<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSoftdeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['dtb_product', 'dtb_user'];
        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'del_flg')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('del_flg');
                    $table->softDeletes();
                });
            }
        }
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
