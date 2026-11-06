<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('platform', ['YouTube', 'TikTok', 'Google Drive', 'Lainnya'])->default('YouTube');
            $table->text('video_url'); // Link asli dari YouTube, TikTok, atau Google Drive
            $table->string('kategori')->default('Dokumentasi Kegiatan'); // Dokumentasi Kegiatan, E-PKKS / Eviden, Pembelajaran, Gelar Karya P5, Profil
            $table->date('tanggal')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_featured')->default(false); // Tayang di beranda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_galleries');
    }
};
