<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('tahun_ajaran')->default('2026/2027');
            $table->string('nama_lengkap');
            $table->string('nik', 25)->nullable();
            $table->string('nisn', 20)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('asal_sekolah')->nullable(); // Nama TK / PAUD / RA
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ortu')->nullable();
            $table->string('no_hp_whatsapp');
            $table->text('alamat_lengkap');
            $table->string('dusun')->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->enum('jalur_pendaftaran', ['Zonasi', 'Afirmasi / KIP', 'Perpindahan Orang Tua'])->default('Zonasi');
            // Upload berkas digital pendaftaran
            $table->string('berkas_kartu_keluarga')->nullable();
            $table->string('berkas_akta_kelahiran')->nullable();
            $table->string('berkas_ijazah_tk')->nullable();
            $table->string('berkas_kip_pkh')->nullable();
            // Status verifikasi panitia
            $table->enum('status_seleksi', [
                'Menunggu Verifikasi', 
                'Berkas Valid', 
                'Perlu Perbaikan Berkas', 
                'Diterima', 
                'Tidak Diterima'
            ])->default('Menunggu Verifikasi');
            $table->text('catatan_panitia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_applicants');
    }
};
