<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    // Method untuk absen
    public function absen(Request $request)
    {
        // Validasi request jika perlu
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,tidak_hadir',
        ]);

        // Logika untuk menyimpan absensi
        $pegawai = Auth::user(); // Ambil data pegawai yang sedang login
        // Misalnya, simpan absensi ke database
        // Absen::create(['pegawai_id' => $pegawai->id, 'tanggal' => $request->tanggal, 'status' => $request->status]);

        return response()->json([
            'message' => 'Absen berhasil dicatat',
            'data' => [
                'pegawai' => $pegawai,
                'tanggal' => $request->tanggal,
                'status' => $request->status,
            ],
        ]);
    }

    // Method untuk logout
    public function logout(Request $request)
    {
        $pegawai = Auth::user();

        // Logika untuk logout
        Auth::logout();

        return response()->json([
            'message' => 'Akun Berhasil Logout',
            'pegawai' => $pegawai,
        ]);
    }
}
