<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('SD Negeri Cinanggerang I');
            $table->string('npsn')->default('20208801');
            $table->string('jenjang')->default('SD');
            $table->string('status_sekolah')->default('Negeri');
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->default('Cinanggerang');
            $table->string('kecamatan')->default('Kec. Pamulihan');
            $table->string('kabupaten')->default('Kab. Sumedang');
            $table->string('provinsi')->default('Prov. Jawa Barat');
            $table->string('kode_pos')->default('45362');
            $table->string('telepon')->nullable();
            $table->string('email')->default('sdncinanggerang1@gmail.com');
            $table->string('website')->nullable();
            $table->string('kepala_sekolah')->default('Aep Yayat, S.Pd');
            $table->string('nip_kepala_sekolah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('sambutan_kepala_sekolah')->nullable();
            $table->string('foto_kepala_sekolah')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner_hero')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
