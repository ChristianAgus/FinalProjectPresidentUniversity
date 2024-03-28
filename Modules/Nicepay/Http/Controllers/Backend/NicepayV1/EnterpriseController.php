<?php

namespace Modules\Nicepay\Http\Controllers\Backend\NicepayV1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Nicepay\Libraries\NicepayEnterprise;
use Modules\Nicepay\Models\Enterprise\Registration;
use Modules\Nicepay\Models\Enterprise\RegistrationResponse;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;
use Modules\Orders\Models\Order;
use Modules\SocialMedia\Models\SocialMedia;
use Modules\Orders\Models\OrderDetail;
use Modules\Orders\Models\OrderShippingAddress;
use Modules\Contacts\Models\Contact;
use Modules\AdminEmail\Models\AdminEmail;
use Mail;

class EnterpriseController extends Controller
{
    protected $nicepayEnterprise;

    public function __construct()
    {
        $this->nicepayEnterprise = new NicepayEnterprise;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->nicepayEnterprise->iMid         = $request->input('iMid');
        $this->nicepayEnterprise->callBackUrl  = $request->input('callBackUrl');
        $this->nicepayEnterprise->dbProcessUrl = $request->input('dbProcessUrl');
        $this->nicepayEnterprise->merchantKey  = $request->input('merchantKey');

        $this->nicepayEnterprise->set('payMethod', $request->input('payMethod'));
        $this->nicepayEnterprise->set('currency', $request->input('currency'));
        $this->nicepayEnterprise->set('amt', $request->input('amt'));
        $this->nicepayEnterprise->set('referenceNo', $request->input('referenceNo'));
        $this->nicepayEnterprise->set('description', $request->input('description'));

        $this->nicepayEnterprise->set('billingNm', $request->input('billingNm'));
        $this->nicepayEnterprise->set('billingPhone', $request->input('billingPhone'));
        $this->nicepayEnterprise->set('billingEmail', $request->input('billingEmail'));
        $this->nicepayEnterprise->set('billingAddr', $request->input('billingAddr'));
        $this->nicepayEnterprise->set('billingCity', $request->input('billingCity'));
        $this->nicepayEnterprise->set('billingState', $request->input('billingState'));
        $this->nicepayEnterprise->set('billingPostCd', $request->input('billingPostCd'));
        $this->nicepayEnterprise->set('billingCountry', $request->input('billingCountry'));

        $this->nicepayEnterprise->set('deliveryNm', $request->input('deliveryNm'));
        $this->nicepayEnterprise->set('deliveryPhone', $request->input('deliveryPhone'));
        $this->nicepayEnterprise->set('deliveryEmail', $request->input('deliveryEmail'));
        $this->nicepayEnterprise->set('deliveryAddr', $request->input('deliveryAddr'));
        $this->nicepayEnterprise->set('deliveryCity', $request->input('deliveryCity'));
        $this->nicepayEnterprise->set('deliveryState', $request->input('deliveryState'));
        $this->nicepayEnterprise->set('deliveryPostCd', $request->input('deliveryPostCd'));
        $this->nicepayEnterprise->set('deliveryCountry', $request->input('deliveryCountry'));

        // credit card
        $this->nicepayEnterprise->set('onePassToken', $request->input('onePassToken'));
        $this->nicepayEnterprise->set('cardExpYymm', $request->input('cardExpYymm'));
        $this->nicepayEnterprise->set('cardCvv', $request->input('cardCvv'));

        // virtual account
        $this->nicepayEnterprise->set('bankCd', $request->input('bankCd'));

        // convenience store, click pay
        $this->nicepayEnterprise->set('mitraCd', $request->input('mitraCd'));

        switch ($request->input('payMethod')) {
            case PaymentMethod::$creditCard:
                $response = $this->nicepayEnterprise->chargeCard();
                break;
            case PaymentMethod::$virtualAccount:
                $response = $this->nicepayEnterprise->requestVA();
                break;
            case PaymentMethod::$cvsConvenienceStore:
                $response = $this->nicepayEnterprise->requestCVS();
                break;
            case PaymentMethod::$clickPay:
                $response = $this->nicepayEnterprise->requestClickPay();
                break;
            case PaymentMethod::$eWallet:
                //$response = $this->nicepayEnterprise->requestEWallet();
                //break;
				    $tgl = date("YmdHis");
					$apiUrl = 'https://api.nicepay.co.id/nicepay/direct/v2/registration';
					$ch = curl_init($apiUrl);
					$referenceNo =  $request->input('referenceNo');

					$jsonData = array(
					  'timeStamp' => $tgl,
					  'iMid' => $request->input('iMid'),
					  'payMethod' => '05',
					  'referenceNo' => $referenceNo,
					  'currency' => 'IDR',
					  'amt' => $request->input('amt'),
					  'goodsNm' => $request->input('description'),
					  'billingNm' => $request->input('billingNm'),
					  'billingPhone' => $request->input('billingPhone'),
					  'billingEmail' => $request->input('billingEmail'),
					  'billingCity' => $request->input('billingCity'),
					  'billingState' => $request->input('billingState'),
					  'billingPostCd' => $request->input('billingPostCd'),
					  'billingCountry' => $request->input('billingCountry'),
					  'dbProcessUrl' => 'https://apps.haldinfoods.com/nicepay/frontend/nicepay-v1/db-process-url',
					  'merchantToken' => hash('sha256',  $tgl. $request->input('iMid').$referenceNo.$request->input('amt').$request->input('merchantKey')),
					  'instmntType' => '2',
					  'instmntMon' => '1',
					   'userIP' => $_SERVER['REMOTE_ADDR'],
					  'cartData' => '{}',
					  'mitraCd' => 'OVOE'
					  );

				   //print_r($jsonData);exit;

					$jsonDataEncoded = json_encode($jsonData);
					curl_setopt($ch, CURLOPT_POST, 1);
					//Attach our encoded JSON string to the POST fields.
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
					//Set the content type to application/json
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
					  // print_r($jsonDataEncoded);exit;
					$curl_result = curl_exec($ch);
					$response = json_decode($curl_result);
				break;
            default:
                $response = $this->nicepayEnterprise->chargeCard();
                break;
        }

        $registration = Registration::create($this->nicepayEnterprise->requestData);

        if (isset($response->resultCd) && $response->resultCd == '0000') {
            // Please save your tXid in your database
            $registration->tXid = $response->tXid;
            $registration->save();

            $registrationResponse = new RegistrationResponse;
            $registrationResponse->fill((array) $response);
            $registrationResponse->data = json_encode($response);
            $registrationResponse->save();

            $order = Order::findOrFail($request->input('referenceNo'));

            // get social media data
            $socialMedia = SocialMedia::all();

            // get order shipping address
            $getOrderShippingAddress = OrderShippingAddress::where('order_id', $order->id)->first();

            $getCarts = OrderDetail::with(['product', 'packagingSize'])->where('order_id', $order->id)->get();

            // get general setting data
            $contact = Contact::first();

            $email = $order->user ? $order->user->email : session()->get('haldin_guest_email');
            $name  = $order->user ? $order->user->name : session()->get('haldin_guest_name');

            $orderPaymentMethod  = $order->getPaymentMethodName();
            $orderPaymentCode    = $order->getPaymentNumber();

            // prepare data
            $data = [
                'getOrderShippingAddress' => $getOrderShippingAddress,
                'orderPaymentMethod'      => $orderPaymentMethod,
                'orderPaymentCode'        => $orderPaymentCode,
                'carts'                   => $getCarts,
                'getOrder'                => $order,
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
                $message->to($email, $name)->subject('Haldin: Order Received');
            });

            $destroyGuestSession = [
                'haldin_guest_title',
                'haldin_guest_name',
                'haldin_guest_email',
                'haldin_guest_province',
                'haldin_guest_city',
                'haldin_guest_postal_code',
                'haldin_guest_courier',
                'haldin_guest_address',
                'haldin_guest_telephone'
            ];
            session()->forget($destroyGuestSession);

           if($request->input('payMethod') == '05'){
			   return redirect()->route('nicepay.frontend.nicepay-v1.enterprise.registration-response', ['id' => $registrationResponse->id, 'mc_token' => $jsonData['merchantToken'], 'txId' => $response->tXid, 'timeStamp' => $tgl, 'amt' => $response->amt]);
			} else { 
			   return redirect()->route('nicepay.frontend.nicepay-v1.enterprise.registration-response', ['id' => $registrationResponse->id]);
			}
        } elseif (isset($response->resultCd)) {
            // API data not correct or error happened in bank system, you can redirect back to checkout page or echo error message.
            // In this sample, we echo error message
            echo '<pre>';
            echo "Oops! Something happened, please notice your system administrator.\n\n";
            echo "result code       : $response->resultCd\n";
            echo "result message    : $response->resultMsg\n";
            echo '</pre>';
        } else {
            // Timeout, you can redirect back to checkout page or echo error message.
            // In this sample, we echo error message
            echo '<pre>Connection Timeout. Please Try again.</pre>';
        }
    }
}
