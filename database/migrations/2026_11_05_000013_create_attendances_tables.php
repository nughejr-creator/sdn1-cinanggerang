<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Presensi / Absensi Guru & Tenaga Kependidikan
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status', [
                'Hadir Tepat Waktu',
                'Hadir Terlambat',
                'Sakit (SKD)',
                'Izin Resmi',
                'Dinas Luar / Pelatihan',
                'Cuti',
                'Tanpa Keterangan (Alpa)'
            ])->default('Hadir Tepat Waktu');
            $table->enum('metode', ['Presensi Mandiri Web', 'Input Manual Admin', 'Fingerprint / Mesin'])->default('Presensi Mandiri Web');
            $table->string('foto_bukti')->nullable(); // Foto selfie kehadiran atau foto surat izin / dokter
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Mencegah duplikasi absensi guru di tanggal yang sama
            $table->unique(['teacher_id', 'tanggal']);
        });

        // 2. Presensi / Absensi Harian Siswa per Rombel
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('student_class_id')->constrained('student_classes')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', [
                'Hadir',
                'Sakit (S)',
                'Izin (I)',
                'Alpa (A)'
            ])->default('Hadir');
            $table->text('keterangan')->nullable();
            $table->string('dicatat_oleh')->nullable(); // Nama guru kelas / wali kelas
            $table->timestamps();

            // Mencegah duplikasi absensi siswa di tanggal yang sama
            $table->unique(['student_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
        Schema::dropIfExists('teacher_attendances');
    }
};
