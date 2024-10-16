<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use App\Models\User;

class PengajuanCutiController extends Controller
{
    // Fungsi untuk mengajukan cuti
    public function pengajuan(Request $request)
    {
        // Validasi input pengguna
        $validatedData = $request->validate([
            'username' => 'required|string|exists:users,username',
            'nama_kelas' => 'required|string|exists:kelas,nama_kelas',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:500',
        ]);

        // Temukan user berdasarkan username
        $user = User::where('username', $validatedData['username'])->firstOrFail();

        // Membuat pengajuan cuti baru
        $pengajuancuti = PengajuanCuti::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'nama_kelas' => $kelas->nama_kelas,
            'tanggal_mulai' => $request,
            'tanggal_selesai' => $request,
            'status' => 'SedangDiProses',
            'keterangan' => $request,
        ]);

        // Mengembalikan respon sederhana
        return response()->json(['message' => 'Cuti berhasil diajukan!'], 201);
    }
}
