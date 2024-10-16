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
            $table->unsignedBigInteger('username');
            $table->string('nama');
            $table->string('nama_kelas');
            $table->enum('status', ['SedangDiProses', 'DiTerima', 'DiTolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
}
