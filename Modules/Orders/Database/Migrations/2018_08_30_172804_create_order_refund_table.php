<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->integer('province_id')->nullable();
            $table->string('province')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number');
            $table->string('reason');
            $table->string('bank_account');
            $table->string('bank_name');
            $table->string('pictures');
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
        Schema::dropIfExists('order_refund');
    }
}
