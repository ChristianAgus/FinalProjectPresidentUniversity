<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'order_id',

        'name',
        'size',
        'uom',
        'price',
        'qty',
        'sub_total'
    ];

    public function products()
    {   
        return $this->belongsTo('App\Models\MsProduct', 'product_id');
    }
    public function orders()
    {   
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}
