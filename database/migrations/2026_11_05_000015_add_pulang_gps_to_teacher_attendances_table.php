<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->decimal('latitude_pulang', 10, 8)->nullable()->after('is_in_radius');
            $table->decimal('longitude_pulang', 11, 8)->nullable()->after('latitude_pulang');
            $table->integer('jarak_pulang_meter')->nullable()->after('longitude_pulang');
            $table->boolean('is_in_radius_pulang')->default(true)->after('jarak_pulang_meter');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_pulang',
                'longitude_pulang',
                'jarak_pulang_meter',
                'is_in_radius_pulang',
            ]);
        });
    }
};
