<?php

namespace Modules\Orders\Http\Controllers\Backend\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Orders\Models\Order;

class OrderShippingMethodController extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_shipping_method.waybill' => ['required'],
        ]);

        $order = Order::findOrFail($id);
        $order->orderShippingMethod->fill($request->input('order_shipping_method'))->save();
        $order->status = $order->status == $order::$statusNew ? $order::$statusSent : $order->status;
        $order->save();

        flash(trans('cms.updated'))->success()->important();

        return redirect()->back();
    }
}
