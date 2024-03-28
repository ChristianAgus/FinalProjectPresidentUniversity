<?php

namespace App\Models\Nicepay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallBackUrl extends Model
{
    use HasFactory;

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
        return $this->belongsTo('App\Models\Nicepay\EnterpriseRegistration', 'tXid');
    }
}
