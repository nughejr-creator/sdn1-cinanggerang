<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah radius geofence di Profil Sekolah (dalam satuan meter, misal 100m)
        Schema::table('school_profiles', function (Blueprint $table) {
            $table->integer('radius_absen_meter')->default(100)->after('longitude');
        });

        // 2. Tambah koordinat GPS presensi guru
        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('metode');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('jarak_meter')->nullable()->after('longitude'); // Jarak guru ke titik sekolah saat absen
            $table->boolean('is_in_radius')->default(true)->after('jarak_meter'); // Validasi apakah dalam zona
        });
    }

    public function down(): void
    {
        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'jarak_meter', 'is_in_radius']);
        });

        Schema::table('school_profiles', function (Blueprint $table) {
            $table->dropColumn(['radius_absen_meter']);
        });
    }
};
