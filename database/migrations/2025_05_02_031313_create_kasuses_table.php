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
        Schema::create('kasuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layanan_id')->nullable();
            $table->foreign('layanan_id')->references('id')->on('layanans')->onDelete('set null');
            $table->string('nama', 255);
            $table->integer('aktif');
            $table->integer('inaktif');
            $table->string('deskripsi', 255)->nullable();
            $table->index(['layanan_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasuses');
    }
};
