<?php

namespace App\Console\Commands;

use App\Services\DapodikWebServiceSync;
use Illuminate\Console\Command;

class DapodikSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dapodik:sync {--token= : Token Web Service Dapodik} {--npsn=20208801 : NPSN Sekolah} {--url=http://localhost:5774 : Base URL Dapodik}';

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

        $this->line("Target URL : {$baseUrl}");
        $this->line("NPSN       : {$npsn}");
        $this->line("Token      : " . substr($token, 0, 4) . '***' . substr($token, -3));
        $this->newLine();

        $this->info("Menghubungi Web Service Dapodik...");

        try {
            $stats = DapodikWebServiceSync::pullFromLocal($token, $npsn, $baseUrl);

            $this->newLine();
            $this->info("🎉 SINKRONISASI BERHASIL!");
            $this->table(
                ['Kategori', 'Jumlah'],
                [
                    ['Total Siswa Diproses', $stats['total']],
                    ['Data Baru Dibuat', $stats['created']],
                    ['Data Diperbarui', $stats['updated']],
                    ['Data Dilewati (Kosong)', $stats['skipped']],
                    ['Jumlah Rombel/Kelas', $stats['classes_synced']],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("❌ Gagal melakukan sinkronisasi: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
