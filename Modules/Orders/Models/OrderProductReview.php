<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProductReview extends Model
{
    protected $fillable = [
        'order_id','product_id',
        'title','review','score',
        
       
    ];

    protected $table = 'order_product_review';

    
}
