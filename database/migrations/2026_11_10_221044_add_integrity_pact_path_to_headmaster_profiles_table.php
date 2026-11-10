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
        Schema::table('headmaster_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('headmaster_profiles', 'integrity_pact_path')) {
                $table->string('integrity_pact_path')->nullable()->after('cv_pdf_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headmaster_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('headmaster_profiles', 'integrity_pact_path')) {
                $table->dropColumn('integrity_pact_path');
            }
        });
    }
};
