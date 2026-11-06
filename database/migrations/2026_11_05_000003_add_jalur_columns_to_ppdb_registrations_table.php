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
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('ppdb_registrations', 'jalur_pendaftaran')) {
                $table->string('jalur_pendaftaran', 40)->default('Zonasi')->after('nisn');
            }
            if (!Schema::hasColumn('ppdb_registrations', 'jarak_ke_sekolah_meter')) {
                $table->integer('jarak_ke_sekolah_meter')->nullable()->after('jalur_pendaftaran');
            }
            if (!Schema::hasColumn('ppdb_registrations', 'nomor_kip_pkh')) {
                $table->string('nomor_kip_pkh', 50)->nullable()->after('jarak_ke_sekolah_meter');
            }
            if (!Schema::hasColumn('ppdb_registrations', 'file_bukti_afirmasi')) {
                $table->string('file_bukti_afirmasi')->nullable()->after('file_ijazah');
            }
            if (!Schema::hasColumn('ppdb_registrations', 'instansi_tujuan_mutasi')) {
                $table->string('instansi_tujuan_mutasi')->nullable()->after('nama_ayah_kandung');
            }
            if (!Schema::hasColumn('ppdb_registrations', 'file_surat_pindah_tugas')) {
                $table->string('file_surat_pindah_tugas')->nullable()->after('file_bukti_afirmasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'jalur_pendaftaran',
                'jarak_ke_sekolah_meter',
                'nomor_kip_pkh',
                'file_bukti_afirmasi',
                'instansi_tujuan_mutasi',
                'file_surat_pindah_tugas',
            ]);
        });
    }
};
