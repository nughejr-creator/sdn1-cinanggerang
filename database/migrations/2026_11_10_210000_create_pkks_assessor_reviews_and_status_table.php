<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kolom pkks_status di tabel school_profiles
        if (Schema::hasTable('school_profiles') && !Schema::hasColumn('school_profiles', 'pkks_status')) {
            Schema::table('school_profiles', function (Blueprint $table) {
                $table->enum('pkks_status', ['active', 'inactive'])->default('active')->after('akreditasi');
            });
        }

        // 2. Tabel Catatan & Rekomendasi Asesor Penilai E-PKKS
        if (!Schema::hasTable('pkks_assessor_reviews')) {
            Schema::create('pkks_assessor_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pkks_period_id')->nullable()->constrained('pkks_periods')->cascadeOnDelete();
                $table->string('assessor_name');
                $table->string('assessor_agency');
                $table->string('recommendation_status'); // 'Sangat Baik / Terpenuhi Penuh', 'Cukup / Perlu Perbaikan', 'Kurang / Eviden Belum Memenuhi'
                $table->text('performance_review')->nullable();
                $table->text('follow_up_recommendation')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pkks_assessor_reviews');
        if (Schema::hasTable('school_profiles') && Schema::hasColumn('school_profiles', 'pkks_status')) {
            Schema::table('school_profiles', function (Blueprint $table) {
                $table->dropColumn('pkks_status');
            });
        }
    }
};
