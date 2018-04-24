<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_user', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->default(User::TYPE_NORMAL_USER);
            $table->integer('introducer_id')->nullable();
            $table->string('user_name')->unique();
            $table->string('email', 50);
            $table->string('name_kana', 50);
            $table->string('ebay_account', 50);
            $table->string('tel', 15)->nullable();
            $table->string('password');
            $table->text('memo')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
