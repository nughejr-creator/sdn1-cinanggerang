<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Artikel & Berita Pendidikan
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori')->default('Berita Sekolah'); // Berita Sekolah, Prestasi, Artikel Pendidikan
            $table->string('thumbnail')->nullable();
            $table->longText('konten');
            $table->enum('status', ['Draft', 'Publish'])->default('Publish');
            $table->date('published_at')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // 2. Pengumuman Resmi Sekolah
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('isi');
            $table->string('file_lampiran')->nullable(); // PDF surat edaran
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Galeri Dokumentasi Kegiatan
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->date('tanggal_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->json('foto')->nullable(); // Multi foto kegiatan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('posts');
    }
};
