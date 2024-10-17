<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use App\Models\User;

class AcceptController extends Controller
{
    // Fungsi untuk menerima pengajuan cuti berdasarkan id
    public function acceptPengajuan(Request $request, $id)
    {
        // Temukan user berdasarkan id
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        // Temukan pengajuan cuti dari user yang memiliki id tersebut dan status 'SedangDiProses'
        $pengajuan = PengajuanCuti::where('user_id', $user->id)
            ->where('status', 'SedangDiProses')
            ->first();

        if (!$pengajuan) {
            return response()->json(['message' => 'Tidak ada pengajuan cuti yang menunggu persetujuan untuk user ini.'], 404);
        }

        // Ubah status menjadi DiTerima
        $pengajuan->update(['status' => 'DiTerima']);

        return response()->json(['message' => 'Pengajuan cuti berhasil diterima.']);
    }

    // Fungsi untuk menolak pengajuan cuti berdasarkan id
    public function rejectPengajuan(Request $request, $id)
    {
        // Temukan user berdasarkan id
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        // Temukan pengajuan cuti dari user yang memiliki id tersebut dan status 'SedangDiProses'
        $pengajuan = PengajuanCuti::where('user_id', $user->id)
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
