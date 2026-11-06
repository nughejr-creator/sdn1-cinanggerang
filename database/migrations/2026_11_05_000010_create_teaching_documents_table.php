<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->enum('semester', ['Ganjil', 'Genap', 'Ganjil & Genap'])->default('Ganjil');
            $table->tinyInteger('tingkat_kelas'); // Kelas 1 s.d 6 (Fase A, B, C)
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->enum('jenis_dokumen', [
                'Modul Ajar / RPP Merdeka',
                'Capaian Pembelajaran (CP)',
                'Alur Tujuan Pembelajaran (ATP)',
                'Tujuan Pembelajaran (TP)',
                'Modul Kokurikuler / Proyek P5',
                'Program Tahunan (Prota) & Promes',
                'Lembar Kerja Peserta Didik (LKPD)',
                'Kisi-kisi & Instrumen Asesmen',
                'Bank Soal / Naskah Ujian',
                'Kriteria Ketercapaian TP (KKTP)',
                'Bahan Ajar / Media Presentasi'
            ])->default('Modul Ajar / RPP Merdeka');
            $table->string('judul_perangkat');
            $table->text('deskripsi')->nullable();
            $table->string('file_dokumen')->nullable(); // Upload file PDF, DOCX, XLSX, PPTX
            $table->string('link_gdrive')->nullable(); // Tautan Google Drive folder berkas
            // Terintegrasi dengan Supervisi Kepala Sekolah (Indikator PKKS No. 9)
            $table->enum('status_telaah', [
                'Draf / Belum Ditelaah',
                'Sudah Ditelaah & Disetujui',
                'Perlu Perbaikan / Revisi'
            ])->default('Draf / Belum Ditelaah');
            $table->text('catatan_kepala_sekolah')->nullable();
            $table->boolean('is_shared')->default(true); // Dibagikan ke sesama guru atau privat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_documents');
    }
};
