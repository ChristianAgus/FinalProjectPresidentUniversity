<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShippingAddress extends Model
{
    protected $fillable = [
        'order_id',
        'title',
        'name',
        'email',

        'phone_number',
        'province_id',
        'province',
        'regency_id',
        'regency',

        'district_id',
        'district',
        'postal_code',
        'address',
    ];

    protected $table = 'order_shipping_addresses';

    
}
