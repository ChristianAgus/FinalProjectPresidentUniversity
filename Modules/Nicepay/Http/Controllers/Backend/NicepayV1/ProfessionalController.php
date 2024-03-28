<?php

namespace Modules\Nicepay\Http\Controllers\Backend\NicepayV1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Nicepay\Libraries\NicepayProfessional;
use Modules\Nicepay\Models\NicepayCode\PaymentMethod;
use Modules\Nicepay\Models\Professional\Registration;

class ProfessionalController extends Controller
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
        $this->nicepayProfessional->iMid         = $request->input('iMid');
        $this->nicepayProfessional->callBackUrl  = $request->input('callBackUrl');
        $this->nicepayProfessional->dbProcessUrl = $request->input('dbProcessUrl');
        $this->nicepayProfessional->merchantKey  = $request->input('merchantKey');

        $this->nicepayProfessional->set('payMethod', $request->input('payMethod'));
        $this->nicepayProfessional->set('currency', $request->input('currency'));
        $this->nicepayProfessional->set('amt', $request->input('amt'));
        $this->nicepayProfessional->set('referenceNo', $request->input('referenceNo'));
        $this->nicepayProfessional->set('goodsNm', $request->input('goodsNm'));

        $this->nicepayProfessional->set('billingNm', $request->input('billingNm'));
        $this->nicepayProfessional->set('billingPhone', $request->input('billingPhone'));
        $this->nicepayProfessional->set('billingEmail', $request->input('billingEmail'));
        $this->nicepayProfessional->set('billingAddr', $request->input('billingAddr'));
        $this->nicepayProfessional->set('billingCity', $request->input('billingCity'));

        $this->nicepayProfessional->set('billingState', $request->input('billingState'));
        $this->nicepayProfessional->set('billingPostCd', $request->input('billingPostCd'));
        $this->nicepayProfessional->set('billingCountry', $request->input('billingCountry'));
        $this->nicepayProfessional->set('deliveryNm', $request->input('deliveryNm'));
        $this->nicepayProfessional->set('deliveryPhone', $request->input('deliveryPhone'));

        $this->nicepayProfessional->set('deliveryAddr', $request->input('deliveryAddr'));
        $this->nicepayProfessional->set('deliveryCity', $request->input('deliveryCity'));
        $this->nicepayProfessional->set('deliveryState', $request->input('deliveryState'));
        $this->nicepayProfessional->set('deliveryPostCd', $request->input('deliveryPostCd'));
        $this->nicepayProfessional->set('deliveryCountry', $request->input('deliveryCountry'));

        $this->nicepayProfessional->set('description', $request->input('description'));
        $this->nicepayProfessional->set('cartData', $request->input('cartData'));
        $this->nicepayProfessional->set('mitraCd', $request->input('mitraCd'));
        $this->nicepayProfessional->set('optDisplayCB', $request->input('optDisplayCB'));
        $this->nicepayProfessional->set('optDisplayBL', $request->input('optDisplayBL'));
        $this->nicepayProfessional->set('isCheckPaymentExptDt', $request->input('isCheckPaymentExptDt'));

        switch ($request->input('payMethod')) {
            case PaymentMethod::$creditCard:
                $response = $this->nicepayProfessional->chargeCard();
                break;
            default:
                $response = $this->nicepayProfessional->chargeCard();
                break;
        }

        $registration = Registration::create($this->nicepayProfessional->requestData);

        if (isset($response->data->resultCd) && $response->data->resultCd == '0000') {
            // Please save your tXid in your database
            $registration->tXid = $response->tXid;
            $registration->save();

            return redirect()->away(
                $response->data->requestURL .
                '?tXid=' . $registration->tXid .
                '&optDisplayCB=' . $registration->optDisplayCB .
                '&optDisplayBL=' . $registration->optDisplayBL .
                '&mitraCd=' . $registration->mitraCd .
                '&isCheckPaymentExptDt=' . $registration->isCheckPaymentExptDt
            );
        } elseif (isset($response->resultCd)) {
            // In this sample, we echo error message
            echo '<pre>';
            echo 'result code    : ' . $response->resultCd . "\n";
            echo 'result message : ' . $response->resultMsg . "\n";
            echo '</pre>';
        } else {
            // In this sample, we echo error message
            echo '<pre>Connection Timeout. Please Try Again.</pre>';
        }
    }
}
