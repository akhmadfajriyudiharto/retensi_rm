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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasiens')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans');
            $table->foreignId('kasus_id')->nullable()->constrained('kasuses');
            $table->foreignId('dokter_id')->nullable()->constrained('dokters');
            $table->date('tanggal_kunjungan');
            $table->date('batas_aktif');
            $table->date('batas_inaktif');
            $table->text('diagnosa')->nullable();
            $table->string('tindakan')->nullable();
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
        Schema::dropIfExists('rekam_medis');
    }
};
