<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id(); // Kolom ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke tabel users
            $table->string('nama'); // Nama pemohon cuti
            $table->string('nama_kelas'); // Nama kelas pemohon cuti
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade');
            $table->string('status')->default('SedangDiProses'); // Status pengajuan cuti
            $table->string('keterangan')->nullable(); // Keterangan tambahan
            $table->date('tanggal_mulai')->nullable(); // Tanggal mulai cuti
            $table->date('tanggal_selesai')->nullable(); // Tanggal selesai cuti
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};