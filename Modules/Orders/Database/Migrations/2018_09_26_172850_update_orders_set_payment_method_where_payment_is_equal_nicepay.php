<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;
use Modules\Orders\Models\Order;

class UpdateOrdersSetPaymentMethodWherePaymentIsEqualNicepay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($orders = Order::all()) {
            foreach ($orders as $order) {
                if ($order->payment == Order::$paymentNicepay) {
                    if (in_array($order->payment_method, ['Credit Card', 'Virtual Account', 'CVS (Convenience Store)'])) {
                        if ($order->payment_method == 'Credit Card') {
                            $order->payment_method = PaymentMethod::$creditCard;
                        } else if ($order->payment_method == 'Virtual Account') {
                            $order->payment_method = PaymentMethod::$virtualAccount;
                        } else if ($order->payment_method == 'CVS (Convenience Store)') {
                            $order->payment_method = PaymentMethod::$cvsConvenienceStore;
                        }
                        $order->save();
                    }
                }
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
