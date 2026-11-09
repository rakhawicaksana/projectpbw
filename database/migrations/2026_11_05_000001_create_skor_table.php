<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skor', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 8)->unique();
            $table->string('inisial', 3);
            $table->unsignedTinyInteger('persen');            // skor kecerdasan 0-100
            $table->unsignedTinyInteger('ketelitian');        // 0-100
            $table->unsignedTinyInteger('kecepatan');         // 0-100
            $table->unsignedSmallInteger('percaya_diri');     // 0-200 (bisa lebih dari 100%)
            $table->unsignedSmallInteger('waktu');            // total detik
            $table->string('kategori');                       // kunci kategori, mis. "terlalu-pd"
            $table->timestamps();

            // urutan leaderboard: skor tertinggi, lalu waktu tercepat
            $table->index(['persen', 'waktu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor');
    }
};
