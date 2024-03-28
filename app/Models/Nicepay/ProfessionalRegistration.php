<?php

namespace App\Models\Nicepay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalRegistration extends Model
{
    use HasFactory;

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
}
