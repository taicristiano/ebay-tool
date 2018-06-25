<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtb_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('dtb_user')->onDelete('cascade');
            $table->unsignedInteger('temp_shipping_method');
            $table->foreign('temp_shipping_method')->references('id')->on('dtb_setting_shipping')->onDelete('cascade');
            $table->string('original_id', 20)->nullable();
            $table->string('item_id', 20);
            $table->smallInteger('original_type')->nullable();
            $table->string('item_name', 200)->nullable();
            $table->string('category_id', 20)->nullable();
            $table->string('category_name', 200)->nullable();
            $table->string('jan_upc', 20)->nullable();
            $table->string('condition_id', 20)->nullable();
            $table->string('condition_name', 200)->nullable();
            $table->text('condition_des')->nullable();
            $table->text('item_des')->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->string('duration', 10)->nullable();
            $table->smallInteger('quantity')->nullable();
            $table->unsignedInteger('shipping_policy_id')->nullable();
            $table->unsignedInteger('payment_policy_id')->nullable();
            $table->unsignedInteger('return_policy_id')->nullable();
            $table->foreign('shipping_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->foreign('payment_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->foreign('return_policy_id')->references('id')->on('dtb_setting_policies')->onDelete('cascade');
            $table->smallInteger('status')->default(1);
            $table->dateTime('day_of_sale')->nullable();
            $table->float('item_height', 10, 2)->nullable();
            $table->float('item_width', 10, 2)->nullable();
            $table->float('item_length', 10, 2)->nullable();
            $table->float('item_weight', 10, 2)->nullable();
            $table->float('pack_material_weight', 10, 2)->nullable();
            $table->float('buy_price', 10, 2)->nullable();
            $table->double('ship_fee', 10, 2)->nullable();
            $table->float('temp_profit', 10, 2)->nullable();
            $table->float('max_price', 10, 2)->nullable();
            $table->float('min_price', 10, 2)->nullable();
            $table->string('keyword', 50)->nullable();
            $table->unsignedInteger('setting_template_id')->nullable();
            $table->foreign('setting_template_id')->references('id')->on('dtb_setting_template')->onDelete('cascade');
            $table->dateTime('last_mornitoring_date')->nullable();
            $table->smallInteger('monitor_type')->default(1);
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
        Schema::dropIfExists('dtb_item');
    }
}
