<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    
    protected function redirectTo()
{
    // Check if the authenticated user has the role of 'admin'
    if (Auth::check() && Auth::user()->role === 'Admin') {
        return '/exhibition/akun/user';
    } elseif (Auth::check() && Auth::user()->role === 'Sales') {
        return '/pameran112023haldin';
    } else {
        // If the user doesn't have a specific role, redirect them to a default route
        return '/';
    }
}
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }
}
