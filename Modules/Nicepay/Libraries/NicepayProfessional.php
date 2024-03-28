<?php

namespace Modules\Nicepay\Libraries;

require_once 'nicepay-php-professional-master/lib/NicepayLib.php';

class NicepayProfessional extends \NicepayLib
{
    public function chargeCard()
    {
        // Populate data
        $this->set('iMid', $this->iMid);
        $this->set('merchantToken', $this->merchantToken());
        $this->set('dbProcessUrl', $this->dbProcessUrl);
        $this->set('callBackUrl', $this->callBackUrl);
        $this->set('instmntMon', '1');
        $this->set('instmntType', '0');
        $this->set('userIP', $this->getUserIP());
        $this->set('goodsNm', $this->get('description'));
        // $this->set('vat', '0');
        // $this->set('fee', '0');
        $this->set('notaxAmt', '0');
        if ($this->get('fee') == '') {
            $this->set('fee', '0');
        }
        if ($this->get('vat') == '') {
            $this->set('vat', '0');
        }
        if ($this->get('cartData') == '') {
            $this->set('cartData', '{}');
        }

//        $this->set('cartData', '{}');
//        var_dump($this->requestData);
//        exit();

        // Check Parameter
        $this->checkParam('iMid', '01');
        $this->checkParam('payMethod', '01');
        $this->checkParam('currency', '03');
        $this->checkParam('amt', '02');
        $this->checkParam('instmntMon', '05');
        $this->checkParam('referenceNo', '06');
        $this->checkParam('goodsNm', '07');
        $this->checkParam('billingNm', '08');
        $this->checkParam('billingPhone', '09');
        $this->checkParam('billingEmail', '10');
        $this->checkParam('billingAddr', '11');
        $this->checkParam('billingCity', '12');
        $this->checkParam('billingState', '13');
        $this->checkParam('billingCountry', '14');
        $this->checkParam('deliveryNm', '15');
        $this->checkParam('deliveryPhone', '16');
        $this->checkParam('deliveryAddr', '17');
        $this->checkParam('deliveryCity', '18');
        $this->checkParam('deliveryState', '19');
        $this->checkParam('deliveryPostCd', '20');
        $this->checkParam('deliveryCountry', '21');
        $this->checkParam('callBackUrl', '22');
        $this->checkParam('dbProcessUrl', '23');
        $this->checkParam('vat', '24');
        $this->checkParam('fee', '25');
        $this->checkParam('notaxAmt', '26');
        $this->checkParam('description', '27');
        $this->checkParam('merchantToken', '28');

//        print_r($this->requestData);
//        exit();
//       Send Request
        $this->request->operation('creditCard');
        $this->request->openSocket();
        $this->resultData = $this->request->apiRequest($this->requestData);
        return $this->resultData;
    }
}
