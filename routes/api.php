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
Route::middleware(['auth:sanctum', 'is.superadmin'])->group(function () {
    // CRUD Pegawai dan Ketua Kelas
    Route::get('/pegawai', [SuperController::class, 'index']); // List all employees
    Route::post('/pegawai/super/create', [SuperController::class, 'store']); // Create Pegawai
    Route::get('/pegawai/super/{username}', [SuperController::class, 'show']); // View employee
    Route::put('/pegawai/super/{username}', [SuperController::class, 'update']); // Update employee
    Route::delete('/pegawai/super/{username}', [SuperController::class, 'destroy']); // Delete employee

    // Promosi dan Demosi
    Route::post('/pegawai/super/{username}/promote', [SuperController::class, 'promoteToKetuaKelas']); // Promote to Ketua Kelas
    Route::post('/pegawai/super/{username}/demote', [SuperController::class, 'demoteKetuaKelas']); // Demote from Ketua Kelas

    // Manajemen Kelas
    Route::get('/kelas/super', [SuperController::class, 'listKelas']); // List all classes
    Route::post('/kelas/super', [SuperController::class, 'createKelas']); // Create class
    Route::delete('/kelas/super/{kelas}', [SuperController::class, 'deleteKelas']); // Delete class
});

//Route Untuk System Admin
Route::middleware(['auth:sanctum', 'is.systemadmin'])->group(function () {
    // CRUD Pegawai
    Route::get('/pegawai/system', [SystemController::class, 'index']); // Menampilkan semua Pegawai dan Ketua Kelas
    Route::post('/pegawai/system', [SystemController::class, 'store']); // Membuat Pegawai baru
    Route::get('/pegawai/system/{username}', [SystemController::class, 'show']); // Menampilkan detail Pegawai
    Route::put('/pegawai/system/{username}', [SystemController::class, 'update']); // Memperbarui Pegawai
});

//route untuk ketua kelas
Route::get('ketua-kelas', [KetuaKelasController::class, 'index']);
Route::post('ketua-kelas', [KetuaKelasController::class, 'store']);
Route::get('ketua-kelas/{username}', [KetuaKelasController::class, 'show']);
Route::put('ketua-kelas/{username}', [KetuaKelasController::class, 'update']);

// Route untuk absensi Pegawai
Route::post('pegawai/register', [AuthController::class, 'register']);
Route::post('pegawai/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'pegawai'])->group(function () {
    Route::post('/pegawai/absen', [PegawaiController::class, 'store']);
    Route::post('/pegawai/logout', [PegawaiController::class, 'logout']);
});


Route::middleware(['auth:sanctum', 'KetuaKelas'])->group(function () {
    Route::post('/ketua/absen', [KetuaKelasController::class, 'store']);
    Route::post('/ketua/logout', [KetuaKelasController::class, 'logout']);
});

//Route Untuk pengajuan cuti pegawai
Route::post('/accept/{username}', [AcceptController::class, 'acceptPengajuan'])->middleware('auth:sanctum', 'cuti.pegawai');
Route::post('/reject/{username', [AcceptController::class, 'rejectPengajuan'])->middleware('auth:sanctum', 'cuti.pegawai');
Route::post('/pengajuan', [PengajuanCutiController::class, 'pengajuan'])->middleware('auth:sanctum');

//Route untuk pengajuan cuti ketua kelas
Route::post('/{username}/accept', [AcceptController::class, 'acceptPengajuan'])->middleware('auth:sanctum', 'cuti.ketua');
Route::post('{username}/reject', [AcceptController::class, 'rejectPengajuan'])->middleware('auth:sanctum', 'cuti.ketua');
Route::post('/pengajuan', [PengajuanCutiController::class, 'pengajuan'])->middleware('auth:sanctum');
