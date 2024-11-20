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
            'jenis_cuti' => 'required|in:Cuti Bulanan,Cuti Mingguan',
            'alamat' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validasi->fails()) {
            return response()->json($validasi->errors(), 422);
        }

        // Buat pengajuan cuti
        $pengajuancuti = PengajuanCuti::create([ 
            'alamat' => $request->input('alamat'),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai' => $request->input('tanggal_selesai'),
            'status' => 'SedangDiProses',
            'keterangan' => $request->input('keterangan'),
            'jenis_cuti' => $request->input('jenis_cuti'),
        ]);

        // Mengembalikan respons JSON dengan pesan sukses dan data pengajuan
        return response()->json(['message' => 'Cuti berhasil diajukan!', 'data' => $pengajuancuti], 201);
    }
}
