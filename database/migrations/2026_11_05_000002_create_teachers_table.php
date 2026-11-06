<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gelar')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('nip')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('jabatan')->default('Guru Kelas'); // Kepala Sekolah, Guru Kelas, Guru Agama, Guru PJOK, Penjaga Sekolah
            $table->string('status_kepegawaian')->default('PNS'); // PNS, PPPK, PPPK Paruh Waktu, Honor Daerah
            $table->string('pendidikan_terakhir')->nullable(); // S1, S2, SMA
            $table->string('jurusan')->nullable();
            $table->string('mapel_diajarkan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
