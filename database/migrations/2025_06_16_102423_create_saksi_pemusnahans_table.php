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
        Schema::create('saksi_pemusnahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_pemusnahan_id')
                ->constrained('berita_acara_pemusnahans')
                ->onDelete('cascade');
            $table->string('nik', 25);
            $table->string('nip', 25)->nullable();
            $table->string('nama', 255);
            $table->string('jabatan', 255);
            $table->string('alamat', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saksi_pemusnahans');
    }
};
