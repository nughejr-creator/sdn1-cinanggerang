<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_classes', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Kelas I, Kelas IIA, Kelas IIB, dll
            $table->tinyInteger('tingkat'); // 1 s.d 6
            $table->string('wali_kelas')->nullable(); // Nama guru wali kelas
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('ruangan')->nullable();
            $table->string('kurikulum')->default('Kurikulum Merdeka');
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_classes');
    }
};
