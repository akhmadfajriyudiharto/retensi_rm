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
        Schema::create('berita_acara_pemusnahans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nip_ketua', 25)->nullable();
            $table->string('nik_ketua', 25);
            $table->string('nama_ketua', 255);
            $table->text('alamat_ketua', 500)->nullable();
            $table->string('kota_pemusnahan', 50);
            $table->string('tempat_pemusnahan', 50);
            $table->text('alamat_pemusnahan', 500)->nullable();
            $table->enum('status', ['proses', 'dikunci', 'dilaksanakan'])->default('proses');
            $table->string('file')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_pemusnahans');
    }
};
