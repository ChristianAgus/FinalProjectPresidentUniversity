<?php

namespace Modules\Nicepay\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckIpAddressNicepay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (config('nicepay.mode') == 'production') {
            if ($_SERVER['REMOTE_ADDR'] != config('nicepay.ip_address_production')) {
                return response()->json(['message' => trans('cms.invalid_ip_address')], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
