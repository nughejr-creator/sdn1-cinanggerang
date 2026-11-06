<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->string('logo_tutwuri')->nullable()->after('logo');
            $table->string('logo_pemda')->nullable()->after('logo_tutwuri'); // Logo Kab. Sumedang
            $table->string('favicon')->nullable()->after('logo_pemda');
            $table->string('foto_bangunan')->nullable()->after('banner_hero'); // Foto Gedung Sekolah
            $table->string('bagan_struktur_organisasi')->nullable()->after('foto_bangunan');
        });
    }

    public function down(): void
    {
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'logo_tutwuri',
                'logo_pemda',
                'favicon',
                'foto_bangunan',
                'bagan_struktur_organisasi',
            ]);
        });
    }
};
