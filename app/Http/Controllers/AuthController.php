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
    public function register(Request $request)
    {
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

        $user = User::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    public function login(Request $request)
    {
        if ($request->getUser() && $request->getPassword()) {
            $credentials = [
                'username' => $request->getUser(),
                'password' => $request->getPassword(),
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return $this->generateResponse($user, $token);
            }

            return response()->json(['message' => 'Username atau password salah'], 401);
        }

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

            return $this->generateResponse($user, $token);
        }

        return response()->json(['message' => 'Username atau password salah'], 401);
    }

    private function generateResponse($user, $token)
    {
        $fitur = [];

        if ($user->jabatan === 'Super Admin') {
            $fitur = [
                'Menus' => [
                    'get_profile' => 'Melihat profil System Admin',
                    'list_pegawai_ketua' => 'Menampilkan semua Pegawai dan Ketua Kelas',
                    'detail_pegawai' => 'Melihat detail Pegawai',
                    'update_pegawai' => 'Memperbarui informasi Pegawai',
                    'list_classes' => 'Melihat semua kelas',
                    'create_class' => 'Membuat kelas baru',
                    'delete_class' => 'Menghapus kelas',
                    'accept_cuti_ketua' => 'Menyetujui cuti dari Ketua Kelas',
                    'reject_cuti_ketua' => 'Menolak cuti dari Ketua Kelas',
                ]
            ];
        } elseif ($user->jabatan === 'System Admin') {
            $fitur = [
                'Menus' => [
                    'get_profile' => 'Melihat profil System Admin',
                    'list_pegawai_ketua' => 'Menampilkan semua Pegawai dan Ketua Kelas',
                    'detail_pegawai' => 'Melihat detail Pegawai',
                    'update_pegawai' => 'Memperbarui informasi Pegawai',
                    'list_classes' => 'Melihat semua kelas',
                    'create_class' => 'Membuat kelas baru',
                    'delete_class' => 'Menghapus kelas',
                    'accept_cuti_ketua' => 'Menyetujui cuti dari Ketua Kelas',
                    'reject_cuti_ketua' => 'Menolak cuti dari Ketua Kelas',
                ]
            ];
        }elseif ($user->jabatan === 'Pegawai') {
            $fitur = [
                'Menus' => [
                   'get_profile_ketua' => 'Melihat profil Ketua Kelas',
                    'create_absen' => 'Membuat absensi',
                    'get_absen' => 'Melihat absensi',
                    'get_pengajuan_cuti' => 'Melihat pengajuan cuti',
                    'delete_absen' => 'Menghapus absensi',
                    'apply_cuti' => 'Mengajukan cuti',
                    'logout' => 'Keluar dari sistem',
                ]
            ];
        }elseif ($user->jabatan === 'Ketua Kelas') {
            $fitur = [
                'Menus' => [
                'get_profile' => 'Melihat profil Ketua Kelas',
                'list_ketua_kelas' => 'Menampilkan seluruh data Ketua Kelas manapun',
                'create_absen' => 'Membuat absensi',
                'get_absen' => 'Melihat absensi',
                'approve_cuti' => 'Menyetujui pengajuan cuti dari Pegawai',
                'reject_cuti' => 'Menolak pengajuan cuti dari Pegawai',
                'apply_cuti' => 'Mengajukan cuti ke Admin',
                'logout' => 'Keluar dari sistem',
                ]
            ];
        }

        return response()->json([
            'userData' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'email' => $user->email,
                'nomor_hp' => $user->nomor_hp,
                'jabatan' => $user->jabatan,
                'nama_kelas' => $user->nama_kelas ?? 'Belum ditambahkan ke dalam kelas.',
            ],
            'fitur' => $fitur,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout'], 201);
    }
}
