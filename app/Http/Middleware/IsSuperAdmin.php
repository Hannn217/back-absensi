<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->jabatan === 'Super Admin') {
            return $next($request);
        }

        return response()->json(['message' => 'Anda Bukan Super Admin, Jangan Cak cak macak'], 403);
    }
}
