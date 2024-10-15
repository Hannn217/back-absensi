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
        Schema::create('super', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama');
            $table->string('username')->unique(); // Username harus unik
            $table->string('email')->unique();
            $table->string('nama_kelas'); // Buat kolom nama_kelas
            $table->foreign('nama_kelas')->references('nama_kelas')->on('kelas')->onDelete('cascade'); // Tambahkan foreign key
            $table->string('nomor_hp');
            $table->timestamps(); // Kolom created_at dan updated_at otomatis diisi oleh Laravel
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super');
    }
};
