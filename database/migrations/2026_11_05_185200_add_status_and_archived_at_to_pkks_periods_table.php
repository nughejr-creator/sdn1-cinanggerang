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
        if (Schema::hasTable('pkks_periods')) {
            Schema::table('pkks_periods', function (Blueprint $table) {
                if (!Schema::hasColumn('pkks_periods', 'status')) {
                    $table->enum('status', ['draft', 'active', 'archived'])->default('active')->after('is_active');
                }
                if (!Schema::hasColumn('pkks_periods', 'archived_at')) {
                    $table->timestamp('archived_at')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pkks_periods')) {
            Schema::table('pkks_periods', function (Blueprint $table) {
                if (Schema::hasColumn('pkks_periods', 'archived_at')) {
                    $table->dropColumn('archived_at');
                }
                if (Schema::hasColumn('pkks_periods', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};
