<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class KetuaKelasMiddleware
{
    public function handle($request, Closure $next)
    {
        // Cek apakah user adalah ketua kelas
        if (Auth::user()->role !== 'Ketua Kelas') {
            return response()->json(['message' => 'Unauthorized. Only Ketua Kelas can accept/reject.'], 403);
        }

        return $next($request);
    }
}
