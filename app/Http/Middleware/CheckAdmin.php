<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Akun;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $akunId = Session::get('akun_id');
        $akun = Akun::find($akunId);

        if (!$akun || !$akun->isAdmin()) {
            return redirect('/home')->with('error', 'Access denied. Admin only.');
        }

        return $next($request);
    }
}