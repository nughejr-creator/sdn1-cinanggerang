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
        // 1. Tabel Pengaturan PPDB
        if (!Schema::hasTable('ppdb_settings')) {
            Schema::create('ppdb_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_ppdb_open')->default(false);
                $table->string('academic_year', 20)->default('2026/2027');
                $table->text('closed_message')->nullable();
                $table->string('whatsapp_contact', 30)->default('081321021585');
                $table->timestamps();
            });
        }

        // 2. Tabel Pendaftar Siswa Baru (PPDB)
        if (!Schema::hasTable('ppdb_registrations')) {
            Schema::create('ppdb_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('registration_number', 50)->unique();
                $table->string('nama_lengkap');
                $table->string('nik', 20)->unique();
                $table->string('nisn', 20)->nullable();
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->string('tempat_lahir');
                $table->date('tanggal_lahir');
                $table->enum('asal_jenjang', ['TK', 'RA', 'PAUD', 'Rumah Tangga'])->default('Rumah Tangga');
                $table->string('nama_asal_sekolah')->nullable();
                $table->string('nama_ibu_kandung');
                $table->string('nama_ayah_kandung')->nullable();
                $table->string('nomor_whatsapp', 30);
                $table->text('alamat_tinggal');
                $table->string('file_kk');
                $table->string('file_akta');
                $table->string('file_ijazah')->nullable();
                $table->enum('status_pendaftaran', ['Menunggu Verifikasi', 'Diterima', 'Ditolak'])->default('Menunggu Verifikasi');
                $table->text('catatan_panitia')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
        Schema::dropIfExists('ppdb_settings');
    }
};
