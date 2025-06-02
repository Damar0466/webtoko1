<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('login')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}