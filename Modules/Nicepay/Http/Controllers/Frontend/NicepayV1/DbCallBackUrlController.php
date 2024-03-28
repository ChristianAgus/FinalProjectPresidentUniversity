<?php

namespace Modules\Nicepay\Http\Controllers\Frontend\NicepayV1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Nicepay\Models\CallBackUrl;

class DbCallBackUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
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
