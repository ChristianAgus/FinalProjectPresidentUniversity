<?php

namespace Modules\Orders\Http\Controllers\Frontend\Orders\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderTransactionStatus;

class ReceivedController extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('status', Order::$statusSent)->firstOrFail();
        $order->status = Order::$statusReceived;
        $order->save();

        return redirect()->back();
    }
}
