<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan pengguna telah login dan merupakan pegawai
        if (Auth::check() && Auth::user()->jabatan == 'Pegawai') {
            return $next($request);
        }

        // Jika pengguna tidak login atau bukan pegawai, batasi akses
        return response()->json([
            'status' => 'error',
            'message' => 'Akses hanya untuk pegawai.',
        ], 403);
    }
}
