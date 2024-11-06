<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use App\Models\User;

class AcceptController extends Controller
{
    // Fungsi untuk menerima pengajuan cuti berdasarkan username
    public function acceptPengajuan($username)
    {
        // Temukan user berdasarkan username
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        // Temukan pengajuan cuti dari user yang memiliki status 'SedangDiProses'
        $pengajuan = PengajuanCuti::where('username', $user->username)
            ->where('status', 'SedangDiProses')
            ->first();

        if (!$pengajuan) {
            return response()->json(['message' => 'Tidak ada pengajuan cuti yang menunggu persetujuan untuk user ini.'], 404);
        }

        // Ubah status menjadi DiTerima
        $pengajuan->update(['status' => 'DiTerima']);

        return response()->json(['message' => 'Pengajuan cuti berhasil diterima.']);
    }

    // Fungsi untuk menolak pengajuan cuti berdasarkan username
    public function rejectPengajuan($username)
    {
        // Temukan user berdasarkan username
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        // Temukan pengajuan cuti dari user yang memiliki status 'SedangDiProses'
        $pengajuan = PengajuanCuti::where('username', $user->username)
            ->where('status', 'SedangDiProses')
            ->first();

        if (!$pengajuan) {
            return response()->json(['message' => 'Tidak ada pengajuan cuti yang menunggu persetujuan untuk user ini.'], 404);
        }

        // Ubah status menjadi DiTolak
        $pengajuan->update(['status' => 'DiTolak']);

        return response()->json(['message' => 'Pengajuan cuti berhasil ditolak.']);
    }
}
