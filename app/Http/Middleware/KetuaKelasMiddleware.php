<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // yang benar

class KetuaKelasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->jabatan === 'Ketua Kelas') {
            return $next($request);
        }
        // Jika pengguna bukan Pegawai, batasi akses
        return response()->json([
            'status' => 'error',
            'message' => 'Hanya Ketua Kelas yang dapat melakukan absensi dan mengajukan cuti.',
        ], 403);
    }
    
}
