<?php

namespace App\Models\Nicepay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterpriseRegistrationResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'resultCd',
        'resultMsg',
        'tXid',
        'referenceNo',

        'payMethod',
        'amount',
        'currency',
        'goodsNm',
        'billingNm',

        'transDt',
        'transTm',
        'description',
        'callbackUrl',
        'authNo',

        'issuBankCd',
        'issuBankNm',
        'cardNo',
        'instmntMon;',
        'istmntType',

        'recurringToken',
        'preauthToken',
        'ccTransType',
        'vat',
        'free',

        'notaxAmt',
        'bankCd',
        'bankVacctNo',
        'vacctValidDt',
        'vacctValidTm',

        'mitraCd',
        'payNo',
        'payValidTm',
        'payValidDt',
        'receiptCode',

        'mRefNo',
        'data',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'nicepay__v1__enterprise__registration__response';
}
