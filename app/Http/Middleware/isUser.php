<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if(Auth::check() && Auth::user()->role == 'user') {
            //kalo uda login dan role nya admin boleh Ke:
             return $next($request);
        }else {
            //jika bukan arahkan kembali ke home
            return redirect()->back()->with('accessDenied', 'Tidak dapat
             mengakses halaman! silahkan login terlebih dahulu.');
        }
    }
}
