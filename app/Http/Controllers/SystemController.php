<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SystemController extends Controller
{
    public function index()
    {
        $users = User::whereIn('jabatan', ['Pegawai', 'Ketua Kelas', 'System Admin'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'email' => $user->email,
                    'nomor_hp' => $user->nomor_hp,
                    'jabatan' => $user->jabatan,
                    'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                ];
            }),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nomor_hp' => 'required',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_hp' => $request->nomor_hp,
            'jabatan' => 'Pegawai',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil ditambahkan',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'nomor_hp' => $user->nomor_hp,
                'jabatan' => $user->jabatan,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    public function show($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if (auth()->user()->jabatan !== 'Super Admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk melihat data ini'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'nomor_hp' => $user->nomor_hp,
                'jabatan' => $user->jabatan,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }

    public function update(Request $request, $username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if (!in_array($user->jabatan, ['Pegawai', 'Ketua Kelas'])) {
            return response()->json(['message' => 'Tidak dapat mengubah data ini'], 403);
        }

        $request->validate([
            'nama' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nomor_hp' => 'required',
            'kelas' => 'required'
        ]);

        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil diperbarui',
            'data' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'nomor_hp' => $user->nomor_hp,
                'jabatan' => $user->jabatan,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }

    
    public function promoteToKetuaKelas($username, Request $request)
    {
        $super = User::where('username', $username)->first();

        if (!$super) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if ($super->jabatan !== 'Pegawai') {
            return response()->json(['message' => 'Hanya Pegawai yang dapat dipromosikan'], 403);
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas',
        ]);

        $existingKetua = User::where('jabatan', 'Ketua Kelas')->exists();

        if ($existingKetua) {
            return response()->json(['message' => 'Sudah ada Ketua Kelas. Hanya satu Ketua Kelas yang diperbolehkan.'], 403);
        }

        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'ketua_kelas_id' => $super->id,
        ]);

        $super->jabatan = 'Ketua Kelas';
        $super->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil dipromosikan menjadi Ketua Kelas dan kelas baru dibuat',
            'data' => [
                'id' => $super->id,
                'nama' => $super->nama,
                'username' => $super->username,
                'email' => $super->email,
                'nomor_hp' => $super->nomor_hp,
                'jabatan' => $super->jabatan,
                'created_at' => $kelas->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $kelas->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
            'kelas' => [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'created_at' => $kelas->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $kelas->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    public function demoteKetuaKelas($username, Request $request)
    {
        $super = User::where('username', $username)->first();

        if (!$super) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        if ($super->jabatan !== 'Ketua Kelas') {
            return response()->json(['message' => 'Hanya Ketua Kelas yang dapat didemote'], 403);
        }

        $kelas = Kelas::where('ketua_kelas_id', $super->id)->first();

        if ($kelas) {
            $kelas->delete();
        }

        $super->jabatan = 'Pegawai';
        $super->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ketua Kelas berhasil didemote menjadi Pegawai dan kelas dihapus',
            'data' => [
                'id' => $super->id,
                'nama' => $super->nama,
                'username' => $super->username,
                'email' => $super->email,
                'nomor_hp' => $super->nomor_hp,
                'jabatan' => $super->jabatan,
                'created_at' => $super->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $super->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }
}
