<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SuperController extends Controller
{
    public function index()
    {
        $super = User::whereIn('jabatan', ['Pegawai', 'Ketua Kelas', 'System Admin'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $super->map(function ($super) {
                return [
                    'id' => $super->id,
                    'nama' => $super->nama,
                    'username' => $super->username,
                    'email' => $super->email,
                    'nomor_hp' => $super->nomor_hp,
                    'jabatan' => $super->jabatan,
                    'created_at' => $super->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'updated_at' => $super->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
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
            'jabatan' => 'required',
            'nama_kelas' => 'required'
        ]);

        $super = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nomor_hp' => $request->nomor_hp,
            'jabatan' => 'Pegawai', // Set default jabatan ke Pegawai
            'nama_kelas' => $request->nama_kelas
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil ditambahkan',
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
            'nama_kelas' => 'required'
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
                'nama_kelas' => $user->nama_kelas,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 200);
    }

    public function destroy($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if (!in_array($user->jabatan, ['Pegawai', 'Ketua Kelas'])) {
            return response()->json(['message' => 'Tidak dapat menghapus data ini'], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil dihapus',
            'data' => [
                'username' => $user->username,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ]
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

        // Validasi nama_kelas dan daftar_anggota
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'daftar_anggota.*' => 'required|array|string', // Validasi untuk daftar anggota
        ]);

        // Hitung jumlah Ketua Kelas yang ada
        $currentKetuaCount = User::where('jabatan', 'Ketua Kelas')->count();

        // Jika sudah ada 5 Ketua Kelas, berikan pesan error
        if ($currentKetuaCount >= 5) {
            return response()->json(['message' => 'Sudah ada 5 Ketua Kelas. Hanya lima Ketua Kelas yang diperbolehkan.'], 403);
        }

        // Update jabatan pengguna menjadi Ketua Kelas
        $super->jabatan = 'Ketua Kelas';
        $super->save();

        // Buat kelas baru dan hubungkan dengan Ketua Kelas
        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'ketua_kelas_id' => $super->id,
            'daftar_anggota' => json_encode($request->daftar_anggota), // Simpan daftar anggota sebagai JSON
        ]);

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
                'nama_kelas' => $request->nama_kelas,
                'kelas_id' => $kelas->id,
                'daftar_anggota' => $request->daftar_anggota,
                'created_at' => $super->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $super->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    public function demoteKetuaKelas($username)
    {
        $super = User::where('username', $username)->first();

        if (!$super) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if ($super->jabatan !== 'Ketua Kelas') {
            return response()->json(['message' => 'Hanya Ketua Kelas yang dapat di-demote'], 403);
        }

        // Ubah jabatan pengguna menjadi Pegawai
        $super->jabatan = 'Pegawai';
        $super->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil di-demote menjadi Pegawai',
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
