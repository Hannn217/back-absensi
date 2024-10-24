<?php

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KetuaKelasController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\AcceptController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute API untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semua rute akan diberikan
| grup middleware "api". Nikmati!
|
*/

// Route untuk registrasi dan autentikasi
Route::post('/register', [AuthController::class, 'register'])->middleware('single.admin'); // Pastikan middleware single.admin sudah diimplementasikan
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route untuk Super Admin
Route::middleware(['auth:sanctum', 'is.superadmin', 'cuti.ketua'])->group(function () {
    // CRUD Pegawai dan Ketua Kelas
    Route::get('/pegawai', [SuperController::class, 'index']); // List all employees
    Route::get('/pegawai/{username}', [SuperController::class, 'show']); // View employee
    Route::put('/pegawai/{username}', [SuperController::class, 'update']); // Update employee
    Route::delete('/pegawai/{username}/del', [SuperController::class, 'destroy']); // Delete employee

    // Promosi dan Demosi
    Route::post('/pegawai/{username}/promote', [SuperController::class, 'promoteToKetuaKelas']); // Promote to Ketua Kelas
    Route::post('/pegawai/{username}/demote', [SuperController::class, 'demoteKetuaKelas']); // Demote from Ketua Kelas

    // Manajemen Kelas
    Route::get('/kelas', [SuperController::class, 'listKelas']); // List all classes
    Route::post('/kelas', [SuperController::class, 'createKelas']); // Create class
    Route::delete('/kelas/{nama_kelas}', [SuperController::class, 'deleteKelas']); // Delete class

    //Manajemen Cuti
    Route::post('/accept/{username}', [AcceptController::class, 'acceptPengajuan']); //untuk menyetujui cuti dari pegawai
    Route::post('/reject/{username}', [AcceptController::class, 'rejectPengajuan']); //untuk menolak cuti dari pegawai
});

//Route Untuk System Admin
Route::middleware(['auth:sanctum', 'is.systemadmin'])->group(function () {
    Route::get('/pegawai', [SystemController::class, 'index']); // Menampilkan semua Pegawai dan Ketua Kelas
    Route::post('/pegawai', [SystemController::class, 'store']); // Membuat Pegawai baru
    Route::get('/pegawai/{username}', [SystemController::class, 'show']); // Menampilkan detail Pegawai
    Route::put('/pegawai/{username}', [SystemController::class, 'update']); // Memperbarui Pegawai
});

//route untuk ketua kelas
Route::middleware(['auth:sanctum', 'KetuaKelasMiddleware', 'cuti.pegawai'])->group(function () {
    Route::post('/absen', [KetuaKelasController::class, 'store']); //unutk absen
    Route::post('/logout', [KetuaKelasController::class, 'logout']); //untuk logout
    Route::post('/accept/{username}', [AcceptController::class, 'acceptPengajuan']); //untuk menyetujui pengajuan cuti dari pegawai
    Route::post('/reject/{username}', [AcceptController::class, 'rejectPengajuan']); //untuk menolak pengajuan cuti dari pegawai
    Route::post('/pengajuan', [PengajuanCutiController::class, 'pengajuan']);
});

// Route untuk Pegawai
Route::middleware(['auth:sanctum', 'PegawaiMiddleware'])->group(function () {
    Route::post('/absen', [PegawaiController::class, 'store']); //untuk absen
    Route::post('/logout', [PegawaiController::class, 'logout']); //untuk logout
    Route::post('/pengajuan', [PengajuanCutiController::class, 'pengajuan']); //untuk mengajukan cuti 
});