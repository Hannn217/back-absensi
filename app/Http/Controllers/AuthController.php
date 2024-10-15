<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        // Middleware akan menangani pemeriksaan jabatan, sehingga validasi hanya fokus pada format
        $validasi = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nomor_hp' => 'required',
            'jabatan' => ['required', Rule::in(['Super Admin', 'System Admin', 'Ketua Kelas', 'Pegawai'])],
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 422);
        }

        // Buat user baru
        $user = User::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,

        ]);

        // Buat token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    // Login
    public function login(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 422);
        }

        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token], 201);
        }

        return response()->json(['message' => 'Username atau password salah'], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout'], 200);
    }
}
