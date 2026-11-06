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
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN status VARCHAR(60) NOT NULL DEFAULT 'Hadir Tepat Waktu'");
        } catch (\Throwable $e) {}

        Schema::table('teacher_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_attendances', 'attendance_type')) {
                $table->string('attendance_type', 30)->default('Reguler')->after('status');
            }
            if (!Schema::hasColumn('teacher_attendances', 'duty_proof_type')) {
                $table->string('duty_proof_type', 50)->nullable()->after('attendance_type');
            }
            if (!Schema::hasColumn('teacher_attendances', 'duty_proof_file')) {
                $table->string('duty_proof_file')->nullable()->after('duty_proof_type');
            }
            if (!Schema::hasColumn('teacher_attendances', 'duty_location_name')) {
                $table->string('duty_location_name')->nullable()->after('duty_proof_file');
            }
            if (!Schema::hasColumn('teacher_attendances', 'duty_description')) {
                $table->text('duty_description')->nullable()->after('duty_location_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_attendances', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_type',
                'duty_proof_type',
                'duty_proof_file',
                'duty_location_name',
                'duty_description',
            ]);
        });
    }
};
