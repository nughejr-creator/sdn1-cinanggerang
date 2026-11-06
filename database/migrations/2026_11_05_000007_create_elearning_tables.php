<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Mata Pelajaran
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // misal: Pendidikan Agama Islam, Bahasa Indonesia, Matematika, IPAS, Bahasa Sunda, PJOK, Seni Budaya
            $table->string('kode')->nullable();
            $table->string('warna')->default('primary');
            $table->timestamps();
        });

        // 2. Materi Pembelajaran (E-Learning Lessons)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('student_class_id')->constrained('student_classes')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi_ringkas')->nullable();
            $table->enum('tipe_materi', ['Teks / Artikel', 'Video YouTube', 'Dokumen PDF / LKPD'])->default('Teks / Artikel');
            $table->longText('konten')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file_materi')->nullable();
            $table->boolean('is_published')->default(true);
            $table->date('tanggal_tayang')->nullable();
            $table->timestamps();
        });

        // 3. Tugas Siswa (Assignments)
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('judul_tugas');
            $table->longText('instruksi_tugas');
            $table->dateTime('batas_waktu'); // Deadline
            $table->string('file_lampiran')->nullable();
            $table->timestamps();
        });

        // 4. Pengumpulan Tugas Siswa (Submissions)
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('catatan_siswa')->nullable();
            $table->string('file_jawaban')->nullable(); // Foto lembar jawaban siswa
            $table->dateTime('waktu_kumpul');
            $table->integer('nilai')->nullable(); // 0 s.d 100
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('courses');
    }
};
