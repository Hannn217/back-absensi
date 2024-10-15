<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class EnsureSingleAdmin
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
        // Cek apakah jabatan yang diminta adalah Super Admin atau System Admin
        if (in_array($request->jabatan, ['Super Admin', 'System Admin'])) {
            // Cek apakah sudah ada user dengan jabatan Super Admin atau System Admin
            $existingAdmin = User::where('jabatan', $request->jabatan)->exists();

            if ($existingAdmin) {
                return response()->json(['message' => 'Hanya satu pengguna yang dapat memiliki jabatan ' . $request->jabatan . '.'], 403);
            }
        }

        return $next($request);
    }
}
