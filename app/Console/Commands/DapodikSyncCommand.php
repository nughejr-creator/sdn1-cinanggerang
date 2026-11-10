<?php

namespace App\Console\Commands;

use App\Services\DapodikWebServiceSync;
use Illuminate\Console\Command;

class DapodikSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dapodik:sync {--token= : Token Web Service Dapodik} {--npsn=20208801 : NPSN Sekolah} {--url=http://127.0.0.1:5774 : Base URL Dapodik} {--no-prune : Jangan hapus siswa yang tidak ada di Dapodik}';

    /**
     * The console command description.
     */
    protected $description = 'Sinkronisasi data Peserta Didik dan Rombel langsung dari Web Service Dapodik Lokal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== SINKRONISASI WEB SERVICE DAPODIK ===");

        $token = $this->option('token') ?: DapodikWebServiceSync::getDefaultToken();
        $npsn = (string)$this->option('npsn');
        $baseUrl = (string)$this->option('url');
        $pruneMissing = !$this->option('no-prune');

        $this->line("Target URL       : {$baseUrl}");
        $this->line("NPSN             : {$npsn}");
        $this->line("Token            : " . substr($token, 0, 4) . '***' . substr($token, -3));
        $this->line("Hapus Siswa Mutasi : " . ($pruneMissing ? 'YA (Hapus Permanen jika tidak ada di Dapodik)' : 'TIDAK'));
        $this->newLine();

        $this->info("Menghubungi Web Service Dapodik...");

        try {
            $stats = DapodikWebServiceSync::pullFromLocal($token, $npsn, $baseUrl, $pruneMissing);

            $this->newLine();
            $this->info("🎉 SINKRONISASI BERHASIL!");
            $this->table(
                ['Kategori', 'Jumlah'],
                [
                    ['Total Siswa Diproses', $stats['total']],
                    ['Data Baru Dibuat', $stats['created']],
                    ['Data Diperbarui (Replace)', $stats['updated']],
                    ['Siswa Naik / Pindah Rombel', $stats['class_changed']],
                    ['Siswa Dihapus Permanen (Mutasi/Keluar)', $stats['deleted']],
                    ['Data Dilewati (Kosong)', $stats['skipped']],
                    ['Jumlah Rombel/Kelas Terpetakan', $stats['classes_synced']],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("❌ Gagal melakukan sinkronisasi: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
