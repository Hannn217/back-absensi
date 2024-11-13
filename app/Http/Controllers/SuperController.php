<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SuperController extends Controller
{
    public function profile()
    {
        // Ambil pengguna yang sedang login
        $user = auth()->user();

        // Jika pengguna tidak ditemukan (misalnya token tidak valid atau tidak ada pengguna yang login)
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan atau belum login'
            ], 404);
        }

        // Jika pengguna ditemukan, kembalikan data profilnya
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
        ], 201);
    }

    public function index()
    {
        $super = User::whereIn('jabatan', ['Pegawai', 'Ketua Kelas', 'System Admin'])
            ->with('kelas') // Load relasi kelas
            ->get();

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
                    'nama_kelas' => $super->kelas ? $super->kelas->nama_kelas : 'Belum Ditambahkan ke dalam kelas', // Ambil dari relasi
                    'created_at' => $super->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    'updated_at' => $super->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                ];
            }),
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

        if (auth()->user()->jabatan != 'Super Admin') {
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
                'nama_kelas' => $user->nama_kelas ?? 'Belum Ditambahkan ke dalam kelas',
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    public function update(Request $request, $username)
    {
        // Cari pengguna berdasarkan username
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Cek apakah jabatan pengguna memungkinkan untuk mengupdate data
        if (!in_array($user->jabatan, ['Pegawai', 'Ketua Kelas'])) {
            return response()->json(['message' => 'Tidak dapat mengubah data ini'], 403);
        }

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nomor_hp' => 'required|string|max:15',
            'nama_kelas' => 'required|string|max:255'
        ]);

        // Pastikan kelas yang diinput valid
        $kelas = Kelas::where('nama_kelas', $request->nama_kelas)->first();
        if (!$kelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelas tidak ditemukan. Pastikan nama_kelas valid.'
            ], 404);
        }

        // Update fields
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'nama_kelas' => $request->nama_kelas, // Pastikan ini diupdate
        ]);

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
                'nama_kelas' => $user->nama_kelas ?? 'Belum Ditambahkan ke dalam kelas', // Pastikan untuk menangani nilai null
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 201);
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
        ], 201);
    }

    public function promoteToKetuaKelas($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if ($user->jabatan === 'Ketua Kelas') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna sudah menjadi Ketua Kelas'
            ], 400);
        }

        $user->jabatan = 'Ketua Kelas';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil dipromosikan menjadi Ketua Kelas'
        ], 201);
    }

    public function demoteKetuaKelas($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if ($user->jabatan !== 'Ketua Kelas') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna bukan Ketua Kelas'
            ], 400);
        }

        $user->jabatan = 'Pegawai';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Ketua Kelas berhasil di-demote menjadi Pegawai'
        ], 201);
    }
}
