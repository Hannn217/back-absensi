<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanCutiTable extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Ganti username menjadi user_id untuk relasi ke tabel users
            $table->foreign('nama')->references('username')->on('users')->onDelete('cascade');
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade'); // Tambahkan foreign key            // Nama pengaju
            $table->enum('status', ['SedangDiProses', 'DiTerima', 'DiTolak'])->default('SedangDiProses'); // Default status
            $table->text('keterangan')->nullable(); // Keterangan pengajuan
            $table->timestamps(); // Timestamps untuk created_at dan updated_at

            // Definisi foreign key untuk user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Definisi foreign key untuk nama_kelas
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_cuti'); // Menghapus tabel saat rollback
    }
}
