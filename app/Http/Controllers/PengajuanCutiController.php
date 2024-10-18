<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator; // Pastikan untuk mengimpor Validator

class PengajuanCutiController extends Controller
{
    // Fungsi untuk mengajukan cuti
    public function pengajuan(Request $request)
    {
        // Validasi input pengguna
        $validasi = Validator::make($request->all(), [
            'username' => 'required|string|max:255|exists:users,username',
            'nama_kelas' => 'required|string|max:255|exists:kelas,nama_kelas',
            'nama' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 422);
        }

        // Buat pengajuan cuti
        $pengajuancuti = PengajuanCuti::create([
            'username' => $request->input('username'), // Mengakses data dengan input()
            'nama_kelas' => $request->input('nama_kelas'),
            'nama' => $request->input('nama'),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'status' => 'SedangDiProses',
            'keterangan' => $request->input('keterangan'),
        ]);

        // Mengembalikan respons JSON dengan pesan sukses dan data pengajuan
        return response()->json(['message' => 'Cuti berhasil diajukan!', 'data' => $pengajuancuti], 201);
    }
}
