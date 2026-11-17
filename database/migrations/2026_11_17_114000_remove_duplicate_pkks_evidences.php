<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PkksEvidence;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus duplikat ID spesifik yang terbentuk saat sinkronisasi seeder
        $duplicateIds = [107060, 107061, 107062, 107063];
        PkksEvidence::whereIn('id', $duplicateIds)->delete();

        // 2. Bersihkan duplikat judul jika masih ada judul ganda pada indikator yang sama
        $duplicates = [
            'Pakta Integritas Kepala Sekolah & Seluruh GTK Tahun 2026',
            'Dokumen RKAS Berbasis Data Rapor Pendidikan (PBD) Tahun 2026',
            'Dokumen Kurikulum Satuan Pendidikan (KSP) Lengkap Tahun 2026',
            'Dokumen Rencana Kegiatan dan Anggaran Sekolah (RKAS) Tahun 2026',
        ];

        foreach ($duplicates as $title) {
            // Jika ada versi TA 2025/2026 yang diedit pengguna, hapus versi template "Tahun 2026"
            $hasCustomVersion = PkksEvidence::where('title', 'LIKE', '%' . str_replace('Tahun 2026', '', $title) . '%TA%')->exists();
            if ($hasCustomVersion) {
                PkksEvidence::where('title', $title)->delete();
            }
        }
    }

    public function down(): void
    {
        // Reversible if needed
    }
};
