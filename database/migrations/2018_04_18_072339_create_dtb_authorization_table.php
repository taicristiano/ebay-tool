<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtbAuthorizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_authorization', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('dtb_user')->onDelete('cascade');
            $table->string('user_code', 7);
            $table->boolean('yahoo_info')->default(false);
            $table->boolean('amazon_info')->default(false);
            $table->boolean('monitoring')->default(false);
            $table->integer('regist_limit');
            $table->integer('post_limit');
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
        Schema::dropIfExists('dtb_authorization');
    }
}
