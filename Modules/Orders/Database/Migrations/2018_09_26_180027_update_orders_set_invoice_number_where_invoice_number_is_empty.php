<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Orders\Models\Order;

class UpdateOrdersSetInvoiceNumberWhereInvoiceNumberIsEmpty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($orders = Order::where('invoice_number', '=', '')->get()) {
            foreach ($orders as $order) {
                $order->setInvoiceNumberById($order->id);
                $order->save();
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
