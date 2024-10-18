<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiPegawai
{
    public function handle(Request $request, Closure $next)
    {
        // Hanya Super Admin, System Admin, atau Ketua Kelas yang bisa menanggapi pengajuan cuti
        if (Auth::check() && in_array(Auth::user()->jabatan, ['Super Admin', 'System Admin', 'Ketua Kelas'])) {
            return $next($request);
        }

        // Jika bukan salah satu dari 3 jabatan di atas, batasi akses
        return response()->json([
            'status' => 'error',
            'message' => 'Hanya Super Admin, System Admin, atau Ketua Kelas yang dapat menanggapi pengajuan cuti.',
        ], 403);
    }
}
