<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for E-PKKS Permendikdasmen No. 23/2026
     */
    public function up(): void
    {
        // 1. Tabel Profil Kepala Sekolah
        if (!Schema::hasTable('headmaster_profiles')) {
            Schema::create('headmaster_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('nip');
                $table->string('nuptk')->nullable();
                $table->string('rank_group')->nullable();
                $table->string('photo_path')->nullable();
                $table->string('cv_pdf_path')->nullable();
                $table->text('leadership_quote')->nullable();
                $table->text('bio_summary')->nullable();
                $table->date('appointment_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Tabel Riwayat Diklat, Prestasi & Organisasi Kepala Sekolah
        if (!Schema::hasTable('headmaster_histories')) {
            Schema::create('headmaster_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('headmaster_profile_id')->constrained('headmaster_profiles')->cascadeOnDelete();
                $table->enum('type', ['education', 'training', 'achievement', 'organization']);
                $table->string('title');
                $table->string('organizer')->nullable();
                $table->string('year')->nullable();
                $table->string('certificate_path')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 3. Tabel Periode Penilaian PKKS
        if (!Schema::hasTable('pkks_periods')) {
            Schema::create('pkks_periods', function (Blueprint $table) {
                $table->id();
                $table->string('academic_year'); // Contoh: "2025/2026"
                $table->string('theme')->nullable();
                $table->date('assessment_date')->nullable();
                $table->string('assessor_name')->nullable();
                $table->string('assessor_pin')->nullable(); // Token/PIN cepat asesor
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Tabel 12 Indikator Mutu PKKS
        if (!Schema::hasTable('pkks_indicators')) {
            Schema::create('pkks_indicators', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pkks_period_id')->constrained('pkks_periods')->cascadeOnDelete();
                $table->integer('indicator_number'); // 1 s/d 12
                $table->enum('competence', ['Kepribadian', 'Sosial', 'Profesional']);
                $table->string('title');
                $table->text('description');
                $table->text('best_practice_notes')->nullable();
                $table->enum('status', ['Ada', 'Tidak Ada'])->default('Ada');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 5. Tabel Bukti Fisik / Eviden PKKS
        // Jika sebelumnya ada pkks_evidences dengan skema lama, kita perbarui atau sesuaikan
        if (Schema::hasTable('pkks_evidences')) {
            // Cek apakah kolom pkks_indicator_id sudah ada
            if (!Schema::hasColumn('pkks_evidences', 'pkks_indicator_id')) {
                Schema::dropIfExists('pkks_evidences');
            }
        }

        if (!Schema::hasTable('pkks_evidences')) {
            Schema::create('pkks_evidences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pkks_indicator_id')->constrained('pkks_indicators')->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('file_type', ['pdf', 'video', 'image', 'link'])->default('pdf');
                $table->string('file_path')->nullable();
                $table->string('external_url')->nullable();
                $table->string('thumbnail_path')->nullable();
                $table->boolean('is_public')->default(true);
                $table->integer('download_count')->default(0);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkks_evidences');
        Schema::dropIfExists('pkks_indicators');
        Schema::dropIfExists('pkks_periods');
        Schema::dropIfExists('headmaster_histories');
        Schema::dropIfExists('headmaster_profiles');
    }
};
