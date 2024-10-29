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
        ], 200);
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

    public function promoteToKetuaKelas($username, Request $request)
    {
        $super = User::where('username', $username)->first();

        // Cek apakah pengguna ditemukan
        if (!$super) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username tidak valid'
            ], 404); // Status 404 untuk username tidak ditemukan
        }

        // Cek apakah jabatan pengguna adalah 'Ketua Kelas'
        if ($super->jabatan === 'Ketua Kelas') {
            // Cek apakah pengguna sudah menjadi ketua dari kelas yang sama
            $existingKelas = Kelas::whereJsonContains('daftar_anggota', $super->username)->first();

            if ($existingKelas) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User ini sudah menjadi ketua kelas di ' . $existingKelas->nama_kelas
                ], 403);
            } else {
                // Menyimpan kelas baru tanpa atribut ketua_kelas
                $kelasBaru = new Kelas();
                $kelasBaru->nama_kelas = $request->nama_kelas; // Menggunakan nama kelas dari request
                $kelasBaru->daftar_anggota = json_encode($request->daftar_anggota); // Menggunakan daftar anggota dari request
                $kelasBaru->save(); // Simpan kelas baru

                return response()->json([
                    'status' => 'success',
                    'message' => 'Kelas baru telah berhasil dibuat: ' . $kelasBaru->nama_kelas
                ], 200);
            }
        }

        // Validasi nama_kelas dan daftar_anggota
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'daftar_anggota' => 'required|array',
            'daftar_anggota.*' => 'required|string',
        ]);

        // Cek apakah nama_kelas sudah ada
        $existingKelas = Kelas::where('nama_kelas', $request->nama_kelas)->first();

        if ($existingKelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nama kelas sudah ada. Pilih nama kelas lain.'
            ], 400);
        }

        // Hitung jumlah Ketua Kelas yang ada
        $currentKetuaCount = User::where('jabatan', 'Ketua Kelas')->count();

        if ($currentKetuaCount >= 5) {
            return response()->json(['status' => 'error', 'message' => 'Sudah ada 5 Ketua Kelas. Hanya lima Ketua Kelas yang diperbolehkan.'], 403);
        }

        // Update jabatan pengguna menjadi Ketua Kelas
        $super->jabatan = 'Ketua Kelas';
        $super->save();

        // Buat kelas baru
        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'daftar_anggota' => json_encode($request->daftar_anggota),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pegawai berhasil dipromosikan menjadi Ketua Kelas dan kelas berhasil dibuat',
            'data' => [
                'id' => $super->id,
                'nama' => $super->nama,
                'username' => $super->username,
                'email' => $super->email,
                'nomor_hp' => $super->nomor_hp,
                'jabatan' => $super->jabatan,
                'nama_kelas' => $kelas->nama_kelas,
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
            'message' => 'Ketua Kelas berhasil di-demote menjadi Pegawai',
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
}
