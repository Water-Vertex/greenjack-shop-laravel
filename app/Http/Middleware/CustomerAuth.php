<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('customer_id')) {
            return redirect()->route('customer.login')->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }
}
