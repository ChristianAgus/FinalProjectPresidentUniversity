<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'oc_number',
        'sales_confirmation',
        'va_by_admin',
        'user_id',
        'order_date',
        'customer_name',
        'customer_phone',
        'customer_address',
        'status',
        'grand_total',
        'proof_of_payment',
        'order_notes',
        'order_date',
        'pay_category',
        'ip_address',
        'cookies',
        'sales_code',
        'sales_name'
    ];
    
    public function salescreate()
    {   
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function salesconfirmation()
    {   
        return $this->belongsTo('App\Models\User', 'sales_confirmation');
    }
}
