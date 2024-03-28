<?php

namespace Modules\Orders\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;
use Modules\AdminEmail\Models\AdminEmail;
use Modules\Contacts\Models\Contact;
use Modules\Nicepay\Models\CheckTransactionStatus;
use Modules\Nicepay\Models\Enterprise\Registration;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;
use Modules\Nicepay\Models\NicepayCode\PaymentStatusCode\CreditCard;
use Modules\Nicepay\Models\NicepayCode\PaymentStatusCode\Cvs;
use Modules\Nicepay\Models\NicepayCode\PaymentStatusCode\VirtualAccount;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderDetail;
use Modules\Orders\Models\OrderShippingAddress;
use Modules\SocialMedia\Models\SocialMedia;

class PaymentStatusCheck extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'orders:payment-status-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payment status in orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $checkTransaction = CheckTransactionStatus::where('status', 0)->get();

        foreach ($checkTransaction as $trans) {
            $updateOrder = Order::where('id', $trans->referenceNo)->first();
            if ($updateOrder) {
                $socialMedia = SocialMedia::all();
                $contact     = Contact::first();
                if ($updateOrder->status == 'Pending' && $updateOrder->payment_status == 0) {
                    $updateOrder                 = Order::where('id', $trans->referenceNo)->first();
                    $updateOrder->status         = Order::$statusNew;
                    $updateOrder->payment_date   = Carbon::parse($trans->transDt . $trans->transTm);
                    $updateOrder->payment_status = Order::$paymentStatusPaid;
                    $updateOrder->save();

                    $misc             = Registration::where('referenceNo', $updateOrder->id)->first();

                    $getOrderShippingAddress = OrderShippingAddress::where('order_id', $updateOrder->id)->first();
                    $getCarts                = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $updateOrder->id)->get();
                    $socialMedia             = SocialMedia::all();
                    $contact                 = Contact::first();

                    $email               = $misc->billingEmail;
                    $name                = $misc->billingNm;

                    $orderPaymentMethod  = $updateOrder->getPaymentMethodName();

                    $data     = [
                        'getOrderShippingAddress' => $getOrderShippingAddress,
                        'carts'                   => $getCarts,
                        'orderPaymentMethod'      => $orderPaymentMethod,
                        'getOrder'                => $updateOrder,
                        'socialMedia'             => $socialMedia,
                        'contact'                 => $contact
                    ];

                    $bcc = AdminEmail::all();

                    foreach ($bcc as $a) {
                        $emails[]=$a->email;
                    }

                    Mail::send('frontend.mail_template.mail_order_confirmation', $data, function ($message) use ($email, $name, $contact, $emails) {
                        //$message->from('', '');
                        $message->bcc($emails);
                        $message->to($email, $name)->subject('Haldin: Order Paid');
                    });
                    
                    $url ='https://apps.haldinfoods.com/api-haldin-agent/api/kirim_email_oc_sales/'.$updateOrder->id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					$data = curl_exec($ch);
					curl_close($ch); 

                    \Log::info($updateOrder);
                    $this->info($updateOrder);
                }
            }
        }

        // get social media data
        $socialMedia = SocialMedia::all();

        // get contact data
        $contact = Contact::first();

        // 1. Select * from order where status = 'Pending' and payment_status = 0 (Unpaid)
        if ($orders = Order::where('payment_status', Order::$paymentStatusUnpaid)->get()) {
            foreach ($orders as $order) {
                // 1.1 If payment == 'nicepay' and has nicepay__v1__check_transaction_status
                if ($order->payment == Order::$paymentNicepay && $nicepayV1CheckTransactionStatuses = $order->nicepayV1CheckTransactionStatuses
                ) {
                    foreach ($nicepayV1CheckTransactionStatuses as $nicepayV1CheckTransactionStatus) {
                        // 1.1.1 If payment method is exist and status == 0 (paid)
                        if (
                            ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$creditCard and $nicepayV1CheckTransactionStatus->status == CreditCard::$success)
                            or ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$virtualAccount and $nicepayV1CheckTransactionStatus->status == VirtualAccount::$paid)
                            or ($nicepayV1CheckTransactionStatus->payMethod == PaymentMethod::$cvsConvenienceStore and $nicepayV1CheckTransactionStatus->status == Cvs::$paid)
                        ) {
                            // 1.1.1.1 Update order set payment date, payment status

                            $order->status         = Order::$statusNew;
                            $order->payment_date   = Carbon::parse($nicepayV1CheckTransactionStatus->transDt . $nicepayV1CheckTransactionStatus->transTm);
                            $order->payment_status = Order::$paymentStatusPaid;
                            $order->save();

                            // 1.1.1.2 Send email

                            // get order data
                            $getOrder = Order::with('user')->findOrFail($order->id);

                            // get carts data
                            $getCarts = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $order->id)->get();

                            // get order shipping address
                            $getOrderShippingAddress = OrderShippingAddress::where('order_id', $order->id)->first();

                            $email = $getOrder->user->email;
                            $name  = $getOrder->user->name;

                            // prepare data
                            $data = [
                                'getOrderShippingAddress' => $getOrderShippingAddress,
                                'carts'                   => $getCarts,
                                'getOrder'                => $getOrder,
                                'socialMedia'             => $socialMedia,
                                'contact'                 => $contact
                            ];

                            // send email
                            $bcc = AdminEmail::all();

                            foreach ($bcc as $a) {
                                $emails[]=$a->email;
                            }

                            Mail::send('frontend.mail_template.mail_order_confirmation', $data, function ($message) use ($email, $name, $contact, $emails) {
                                //$message->from('', '');
                                $message->bcc($emails);
                                $message->to($email, $name)->subject('Haldin: Order Paid');
                            });

                            $message = 'Order id: ' . $order->id . ', payment_date: ' . $order->payment_date . ', payment_status: ' . $order->payment_status;
                            \Log::info($message);
                            $this->info($message);
                        }
                    }
                }
            }
        }
    }
}
