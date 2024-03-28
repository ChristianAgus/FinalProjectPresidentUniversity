<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShippingMethod extends Model
{
    protected $fillable = [
        'order_id',
        'waybill',
        'code',
        'name',
        'service',
        'description',
        'cost'
    ];

    protected $table = 'order_shipping_method';

    public function getCostFormat()
    {
        return number_format($this->cost, 2, ',', '.');
    }
}
