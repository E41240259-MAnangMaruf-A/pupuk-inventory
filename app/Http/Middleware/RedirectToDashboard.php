<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToDashboard
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            switch (Auth::user()->role) {
                case 'admin_desa':
                    return redirect()->route('dashboard.admin-desa');
                case 'admin_koperasi':
                    return redirect()->route('dashboard.admin-koperasi');
                case 'kasir_koperasi':
                    return redirect()->route('dashboard.kasir-koperasi');
                case 'kepala_desa':
                    return redirect()->route('dashboard.kepala-desa');
            }
        } else {
            return route('signin');
        }

        return $next($request);
    }
}
