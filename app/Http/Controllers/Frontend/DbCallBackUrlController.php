<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nicepay\CallBackUrl;

class DbCallBackUrlController extends Controller
{

    public function index(Request $request)
    {
        $callBackUrl = new CallBackUrl;
        $callBackUrl->fill($request->input());
        $callBackUrl->data = json_encode($request->input());
        $callBackUrl->save();

        $data['callBackUrl'] = $callBackUrl;

        return view()->first(
            [
                'frontend/nicepay-v1/db-call-back-url/index/'.$callBackUrl->transactionRegistration->payMethod,
                'nicepay::frontend/nicepay-v1/db-call-back-url/index/'.$callBackUrl->transactionRegistration->payMethod,
                'frontend/nicepay-v1/db-call-back-url/index/default',
                'nicepay::frontend/nicepay-v1/db-call-back-url/index/default',
            ],
            $data
        );
    }
}
