<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('Admin')->after('email'); // Admin, Asisten Admin, Guru
            $table->foreignId('teacher_id')->nullable()->after('role')->constrained('teachers')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['role', 'teacher_id', 'is_active']);
        });
    }
};
