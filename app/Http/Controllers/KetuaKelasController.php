<?php

namespace App\Http\Controllers;

use App\Models\KetuaKelas;
use Illuminate\Http\Request;

class KetuaKelasController extends Controller
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
                'nama_kelas' => $user->nama_kelas,
                'created_at' => $user->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    // Menyimpan data ketua baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'required|in:hadir,izin,sakit',
            'alasan' => 'required|string|max:255',
            'nama_kelas' => 'required|exists:kelas,nama_kelas',
            'date' => 'required|date',
        ]);

        $ketua = KetuaKelas::create($request->all());

        return response()->json([
            'message' => 'Ketua Kelas berhasil melakukan absen.',
            'ketua' => $ketua,
        ], 201);
    }

    // Menghapus data ketua dari database berdasarkan username
    public function destroy($username)
    {
        $ketua = KetuaKelas::where('username', $username)->firstOrFail();
        $ketua->delete();

        return response()->json([
            'message' => 'Data ketua berhasil dihapus.',
        ], 201);
    }
}
