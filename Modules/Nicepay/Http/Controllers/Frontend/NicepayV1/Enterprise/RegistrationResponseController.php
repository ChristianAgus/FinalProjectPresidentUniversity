<?php

namespace Modules\Nicepay\Http\Controllers\Frontend\NicepayV1\Enterprise;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Nicepay\Models\Enterprise\RegistrationResponse;
use Modules\Orders\Models\Order;

class RegistrationResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if (session()->has('haldin_user_active')) {
            $userMemberId = session()->get('haldin_user_id');
        } else {
            $userMemberId = 0;
        }
		
        $registrationResponse = RegistrationResponse::findOrFail($request->query('id'));
		
        if ($registrationResponse) {
            $data['registrationResponse']      = $registrationResponse;
			
			if($registrationResponse->payMethod == '05'){
				$data['token_merchant'] = $request->mc_token;
				$data['amt']            = $request->amt;
				$data['timeStamp']      = $request->timeStamp;
				$data['txId']           = $request->txId;
				
				$data['order'] = Order::where('id', $registrationResponse->referenceNo)->where('user_member_id', $userMemberId)->firstOrFail();
			} else {
				$data['registrationResponseOrder'] = Order::where('id', $registrationResponse->referenceNo)->where('user_member_id', $userMemberId)->firstOrFail();
			}
			
			$data['title'] = trans('cms.payment_confirmation') . ' | Haldin Foods';
			

            return view()->first(
                [
                    'frontend/nicepay-v1/enterprise/registration-response/index/' . $registrationResponse->payMethod,
                    'nicepay::frontend/nicepay-v1/enterprise/registration-response/index/' . $registrationResponse->payMethod,
                    'frontend/nicepay-v1/enterprise/registration-response/index/default',
                    'nicepay::frontend/nicepay-v1/enterprise/registration-response/index/default',
                ],
                $data
            );
        } else {
            return redirect()->back();
        }
    }
}
