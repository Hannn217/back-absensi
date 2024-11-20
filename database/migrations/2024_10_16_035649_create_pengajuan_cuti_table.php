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
            $table->id(); // Klom ID
            $table->string('alamat');
            $table->string('status')->default('SedangDiProses'); // Status pengajuan cuti
            $table->string('keterangan')->nullable(); // Keterangan tambahan
            $table->date('tanggal_mulai')->nullable(); // Tanggal mulai cuti
            $table->date('tanggal_selesai')->nullable(); 
            $table->enum('jenis_cuti', ['Cuti Bulanan', 'Cuti Mingguan']);
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
