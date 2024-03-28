<?php

namespace Modules\Nicepay\Models\Professional;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $attributes = [
        'currency' => 'IDR',
        'goodsNm' => 'Merchant Goods 1',
        'description' => 'this is test order',
        'optDisplayCB' => '0',
        'optDisplayBL' => '0',
        'isCheckPaymentExptDt' => '1',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'nicepay__v1__professional__registration';

    public function callBackUrl()
    {
        return $this->hasOne(\Modules\Nicepay\Models\CallBackUrl::class, 'tXid', 'tXid');
    }

    public function dbProcessUrl()
    {
        return $this->hasOne(\Modules\Nicepay\Models\DbProcessUrl::class, 'tXid', 'tXid');
    }

    public function getIsCheckPaymentExptDtOptions()
    {
        return [
            '0' => 'false',
            '1' => 'true',
        ];
    }

    public function getMerchantToken()
    {
        return hash('sha256', $this->iMid.$this->referenceNo.$this->amt.config('nicepay.merchant_key'));
    }

    public function getOptDisplayBLOptions()
    {
        return [
            '0' => 'false',
            '1' => 'true',
        ];
    }

    public function getOptDisplayCB()
    {
        $optDisplayCB = $this->optDisplayCB;

        if ($this->payMethod) {
            $optDisplayCB = 1;
        }

        return $optDisplayCB;
    }

    public function getOptDisplayCBOptions()
    {
        return [
            '0' => 'false',
            '1' => 'true',
        ];
    }

    public function getRecurrOptOptions()
    {
        return [
            '0' => 'Automatic Cancel',
            '1' => 'Do not cancel',
            '2' => 'Do not make token',
        ];
    }
}
