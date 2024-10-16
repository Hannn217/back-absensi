<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;

class PengajuanCutiController extends Controller
{
    // Fungsi untuk menerima pengajuan cuti berdasarkan username
    public function acceptPengajuan(Request $request, $username)
    {
        // Temukan pengajuan cuti dari user yang memiliki username tersebut dan status 'pending'
        $pengajuan = PengajuanCuti::whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        })->where('status', 'SedangDiProses')->first();

        // Jika tidak ada pengajuan yang 'pending'
        if (!$pengajuan) {
            return response()->json(['message' => 'Tidakk ada pengajuan cuti yang menunggu persetujuan untuk user ini.'], 404);
        }

        // Ubah status menjadi accepted
        $pengajuan->update([
            'status' => 'accepted',
        ]);

        return response()->json(['message' => 'Pengajuan cuti berhasil diterima.']);
    }

    // Fungsi untuk menolak pengajuan cuti berdasarkan username
    public function rejectPengajuan(Request $request, $username)
    {
        // Temukan pengajuan cuti dari user yang memiliki username tersebut dan status 'pending'
        $pengajuan = PengajuanCuti::whereHas('user', function ($query) use ($username) {
            $query->where('username', $username);
        })->where('status', 'SedangDiProses')->first();

        if (!$pengajuan) {
            return response()->json(['message' => 'Tidak ada pengajuan cuti yang menunggu persetujuan untuk user ini.'], 404);
        }

        // Ubah status menjadi rejected
        $pengajuan->update([
            'status' => 'rejected',
        ]);

        return response()->json(['message' => 'Pengajuan cuti berhasil ditolak.']);
    }
}
