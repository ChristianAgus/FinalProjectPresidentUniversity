<?php

namespace Modules\Orders\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTransactionStatus extends Model
{
    protected $fillable = [
        'order_id',
        'status',
    ];

    protected $table = 'order_transaction_status';

    public function order()
    {
        return $this->belongsTo(\Modules\Orders\Models\Order::class, 'order_id')->withTrashed();
    }
}
