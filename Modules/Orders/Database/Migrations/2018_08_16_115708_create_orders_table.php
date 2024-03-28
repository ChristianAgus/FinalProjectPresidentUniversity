<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no');
            $table->integer('user_member_id')->nullable()->comment('if zero = guest');
            $table->string('status')->comment('Pending, New, Sent, Received, Completed, Returned');
            $table->string('payment');
            $table->string('payment_method')->comment('ex. 01, 02, 03');
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_fee_formula')->nullable();
            $table->integer('payment_status')->comment('0 = Unpaid, 1 = Paid');
            $table->bigInteger('subtotal');
            $table->integer('tax')->comment('from subtotal');
            $table->integer('total_weight');
            $table->integer('total_shipping_cost')->comment('round(total_weight)*order_shipping_method.cost');
            $table->integer('payment_fee')->nullable();
            $table->integer('grand_total')->comment('subtotal + tax + total_shipping_cost + payment_fee - voucher_value');
            $table->text('notes')->nullable();
            $table->integer('voucher_id')->nullable();
            $table->string('voucher_code')->nullable();
            $table->integer('voucher_value')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
