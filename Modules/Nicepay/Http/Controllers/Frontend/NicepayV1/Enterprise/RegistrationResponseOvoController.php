<?php

namespace Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\Enterprise;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RegistrationResponseOvoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
       

        //if ($request->resultCd) {           
            $data['title'] = trans('cms.payment_confirmation') . ' | Haldin Foods';
			$data['code']  = $request->resultCd;
			$data['pesan'] = $request->resultMsg;

			
			return view('nicepay::frontend/nicepay-v1/enterprise/registration-response/index/ovo', $data);
       /*   } else {
            return redirect()->back();
        }  */
    }
}
