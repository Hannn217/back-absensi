<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Hanya Pegawai yang bisa melanjutkan
        if (Auth::check() && Auth::user()->jabatan == 'Pegawai') {
            return $next($request);
        }

        // Jika pengguna bukan Pegawai, batasi akses
        return response()->json([
            'status' => 'error',
            'message' => 'Hanya Pegawai yang dapat melakukan absensi dan mengajukan cuti.',
        ], 403);
    }
}
