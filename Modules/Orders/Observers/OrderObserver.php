<?php

namespace Modules\Orders\Observers;

use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderTransactionStatus;

class OrderObserver
{
    public $order;
    public $orderTransactionStatusStore = false;

    public function __construct()
    {
        //
    }

    public function creating(Order $order)
    {
        //
    }

    public function created(Order $order)
    {
        //
    }

    public function updating(Order $order)
    {
        //
    }

    public function updated(Order $order)
    {
        //
    }

    public function saving(Order $order)
    {
        //
    }

    public function saved(Order $order)
    {
        if ($order->status != $order->getOriginal('status')) {
            $this->order = $order;
            $this->orderTransactionStatusStore = true;
        }
    }

    public function __destruct()
    {
        if ($this->orderTransactionStatusStore === true) {
            OrderTransactionStatus::create(['order_id' => $this->order->id, 'status' => $this->order->status]);
        }
    }
}
