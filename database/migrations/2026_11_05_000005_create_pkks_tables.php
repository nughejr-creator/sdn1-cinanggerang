<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Standar / Kompetensi PKKS (Kepribadian, Sosial, Profesional)
        Schema::create('pkks_komponens', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Kepribadian, Sosial, Profesional
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 2. 12 Indikator Resmi Permendikdasmen No. 23/2026
        Schema::create('pkks_indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkks_komponen_id')->constrained('pkks_komponens')->cascadeOnDelete();
            $table->integer('nomor'); // 1 s.d 12
            $table->text('indikator');
            $table->longText('bukti_fisik_usulan')->nullable();
            $table->timestamps();
        });

        // 3. Dokumen Eviden / Bukti Fisik yang diunggah
        Schema::create('pkks_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkks_indikator_id')->constrained('pkks_indikators')->cascadeOnDelete();
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->string('judul_dokumen');
            $table->text('deskripsi')->nullable();
            $table->string('file_pdf')->nullable(); // Upload PDF berkas fisik
            $table->string('link_eksternal')->nullable(); // Link GDrive / Video kegiatan
            $table->enum('status_keterpenuhan', ['Ada', 'Tidak Ada'])->default('Ada');
            $table->text('catatan_asesor')->nullable(); // Catatan pengawas saat monev
            $table->string('dinilai_oleh')->nullable(); // Nama Pengawas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkks_evidences');
        Schema::dropIfExists('pkks_indikators');
        Schema::dropIfExists('pkks_komponens');
    }
};
