<?php

namespace Modules\Nicepay\Http\Controllers\Frontend\NicepayV1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Mail;
use Modules\AdminEmail\Models\AdminEmail;
use Modules\Contacts\Models\Contact;
use Modules\Nicepay\Libraries\NicepayProfessional;
use Modules\Nicepay\Models\CheckTransactionStatus;
use Modules\Nicepay\Models\DbProcessUrl;
use Modules\Nicepay\Models\Enterprise\Registration;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderDetail;
use Modules\Orders\Models\OrderShippingAddress;
use Modules\SocialMedia\Models\SocialMedia;

class DbProcessUrlController extends Controller
{
    protected $nicepayProfessional;

    public function __construct()
    {
        $this->nicepayProfessional = new NicepayProfessional;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $dbProcessUrl= new DbProcessUrl;
        $dbProcessUrl->fill($request->input());
        $dbProcessUrl->data = json_encode($request->input());
        $dbProcessUrl->save();

        if ($dbProcessUrl) {
            // 2. Check Transaction Status
            $pushedToken = $dbProcessUrl->merchantToken;
            //dd($pushedToken);
            $this->nicepayProfessional->set('tXid', $dbProcessUrl->tXid);
            $this->nicepayProfessional->set('referenceNo', $dbProcessUrl->referenceNo);
            $this->nicepayProfessional->set('amt', $dbProcessUrl->amt);
            $this->nicepayProfessional->set('iMid', config('nicepay.imid'));
            $merchantToken = $this->nicepayProfessional->merchantTokenC();
            
            $this->nicepayProfessional->set('merchantToken', $merchantToken);

            // 2.1 Request To Nicepay
            $paymentStatus = $this->nicepayProfessional->checkPaymentStatus($dbProcessUrl->tXid, $dbProcessUrl->referenceNo, $dbProcessUrl->amt);

            $dbProcessUrl->request_log = 'txid: ' . $dbProcessUrl->tXid . 'ref: ' . $dbProcessUrl->referenceNo . 'amt: ' . $dbProcessUrl->amt;
            $dbProcessUrl->save();

            // 2.2 Response From Nicepay
            if ($pushedToken == $merchantToken) {
                // 2.2.1 Insert into CheckTransactionStatus
                $checkTransactionStatus = new CheckTransactionStatus;
                //dd($checkTransactionStatus);
                $checkTransactionStatus->fill((array) $paymentStatus);
                $checkTransactionStatus->data = json_encode($paymentStatus);
                $checkTransactionStatus->save();

                if (isset($paymentStatus->status) && $paymentStatus->status == '0') {
                    $updateOrder                 = Order::where('id', $checkTransactionStatus->referenceNo)->first();
                    //$updateOrder->status         = Order::$statusNew;
                    $updateOrder->payment_date   = Carbon::parse($checkTransactionStatus->transDt . $checkTransactionStatus->transTm);
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
                    
                    $cek_provider = DB::select("provider FROM user_members WHERE email='$email'");
					
					foreach($cek_provider as $ck){
						$usr_prov = $ck->provider;
					}
					
                    if (($usr_prov != 'Sales') or ($usr_prov != 'Customer') or ($usr_prov != 'Agent') or ($usr_prov !='Karyawan')) {
                    Mail::send('frontend.mail_template.mail_order_confirmation', $data, function ($message) use ($email, $name, $contact, $emails) {
                        //$message->from('', '');
                        $message->bcc($emails);
                        $message->to($email, $name)->subject('Haldin: Order Paid');
                    });
                    }
                    
                    echo '<pre>Success</pre>';
                    
                    /*$url ='https://www.haldinfoods.com/api-haldin-agent/api/kirim_email_oc_sales/'.$updateOrder->id;
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					$data = curl_exec($ch);
					curl_close($ch);*/  
					
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '1') {
                    echo '<pre>Void</pre>';
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '2') {
                    echo '<pre>Refund</pre>';
                } elseif (isset($paymentStatus->status) && $paymentStatus->status == '9') {
                    echo '<pre>Reversal</pre>';
                } else {
                    echo '<pre>Status Unknown</pre>';
                }
            }
        } else {
            Log::error('Cant Create dbProcessUrl #error101');
            echo '<pre>Cant Create dbProcessUrl</pre>';
        }
    }

    public function tesNicepay($id)
    {
        $dbProcessUrl  = DbProcessUrl::where('referenceNo', $id)->first();

        $pushedToken = $dbProcessUrl->merchantToken;

        $this->nicepayProfessional->set('tXid', $dbProcessUrl->tXid);
        $this->nicepayProfessional->set('referenceNo', $dbProcessUrl->referenceNo);
        $this->nicepayProfessional->set('amt', $dbProcessUrl->amt);
        $this->nicepayProfessional->set('iMid', config('nicepay.imid'));
        $merchantToken = $this->nicepayProfessional->merchantTokenC();
        $this->nicepayProfessional->set('merchantToken', $merchantToken);

        $paymentStatus = $this->nicepayProfessional->checkPaymentStatus($dbProcessUrl->tXid, $dbProcessUrl->referenceNo, $dbProcessUrl->amt);

        // $checkTransactionStatus = new CheckTransactionStatus;
        // $checkTransactionStatus->fill((array) $paymentStatus);
        // $checkTransactionStatus->data = json_encode($paymentStatus);
        // $checkTransactionStatus->save();

        if ($pushedToken == $merchantToken) {
            // 2.2.1 Insert into CheckTransactionStatus
            $checkTransactionStatus = new CheckTransactionStatus;
            $checkTransactionStatus->fill((array) $paymentStatus);
            $checkTransactionStatus->data = json_encode($paymentStatus);
            $checkTransactionStatus->save();

            if (isset($paymentStatus->status) && $paymentStatus->status == '0') {
                echo '<pre>Success</pre>';
            } elseif (isset($paymentStatus->status) && $paymentStatus->status == '1') {
                echo '<pre>Void</pre>';
            } elseif (isset($paymentStatus->status) && $paymentStatus->status == '2') {
                echo '<pre>Refund</pre>';
            } elseif (isset($paymentStatus->status) && $paymentStatus->status == '9') {
                echo '<pre>Reversal</pre>';
            } else {
                echo '<pre>Status Unknown</pre>';
            }
        }

        dd($checkTransactionStatus);
    }
}
