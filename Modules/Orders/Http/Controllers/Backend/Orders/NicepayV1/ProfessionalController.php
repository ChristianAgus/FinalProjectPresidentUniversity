<?php

namespace Modules\Orders\Http\Controllers\Backend\Orders\NicepayV1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Nicepay\Models\NicepayCode;
use Modules\Orders\Models\Order;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['nicepayCode'] = new NicepayCode;
        $data['orders'] = Order::where('payment_status', Order::$paymentStatusUnpaid)->get();
        $data['title'] = '';
        return view('orders::backend/orders/nicepay-v1/professional/index', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(\Modules\Orders\Http\Requests\Backend\Orders\NicepayV1\StoreRequest $request)
    {
        $order = Order::findOrFail($request->input('id'));

        $data['nicepayCode'] = new NicepayCode;
        $data['registration'] = $order->getNicepayV1ProfessionalRegistration($request->input());
        return view('nicepay::backend/nicepay-v1/professional/create', $data);
    }
}
