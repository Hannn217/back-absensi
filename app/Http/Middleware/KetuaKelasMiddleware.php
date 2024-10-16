<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class KetuaKelasMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->jabatan === 'Ketua Kelas') {
            return $next($request);
        }

        return response()->json(['message' => 'Anda Bukan Super Admin, Jangan Cak cak macak'], 403);
    }
}
