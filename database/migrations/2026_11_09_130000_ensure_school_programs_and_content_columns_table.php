<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel School Programs
        if (!Schema::hasTable('school_programs')) {
            Schema::create('school_programs', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category', 40); // ekstrakurikuler, kokurikuler, pembiasaan
                $table->text('description')->nullable();
                $table->foreignId('created_by')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('category');
            });
        }

        // 2. Kolom Tambahan di Posts
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (!Schema::hasColumn('posts', 'program_id')) {
                    $table->foreignId('program_id')->nullable();
                }
                if (!Schema::hasColumn('posts', 'program_category')) {
                    $table->string('program_category', 40)->nullable();
                }
                if (!Schema::hasColumn('posts', 'tampil_di_beranda')) {
                    $table->boolean('tampil_di_beranda')->default(false);
                }
            });
        }

        // 3. Kolom Tambahan di Announcements
        if (Schema::hasTable('announcements')) {
            Schema::table('announcements', function (Blueprint $table) {
                if (!Schema::hasColumn('announcements', 'tampil_di_beranda')) {
                    $table->boolean('tampil_di_beranda')->default(true);
                }
            });
        }

        // 4. Kolom Tambahan di School Profiles
        if (Schema::hasTable('school_profiles')) {
            Schema::table('school_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('school_profiles', 'ticker_text')) {
                    $table->text('ticker_text')->nullable();
                }
                if (!Schema::hasColumn('school_profiles', 'is_ticker_active')) {
                    $table->boolean('is_ticker_active')->default(true);
                }
            });
        }

        // 5. Kolom Tambahan di Users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role', 40)->default('Guru');
                }
                if (!Schema::hasColumn('users', 'teacher_id')) {
                    $table->foreignId('teacher_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }
    }

    public function down(): void
    {
        // Safe rollback
    }
};
