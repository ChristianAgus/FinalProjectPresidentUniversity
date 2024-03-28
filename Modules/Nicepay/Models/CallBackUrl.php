<?php

namespace Modules\Nicepay\Models;

use Illuminate\Database\Eloquent\Model;

class CallBackUrl extends Model
{
    protected $fillable = [
        'resultCd',
        'resultMsg',
        'tXid',
        'referenceNo',

        'amount',
        'transDt',
        'transTm',
        'description',
        'receiptCode',

        'payNo',
        'mitraCd',
        'authNo',
        'bankVacctNo',
        'bankCd',

        'data',
    ];
    
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'nicepay__v1__call_back_url';

    public function transactionRegistration()
    {
        return $this->belongsTo(\Modules\Nicepay\Models\Professional\Registration::class, 'tXid', 'tXid');
    }
}
