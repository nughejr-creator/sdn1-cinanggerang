<?php

namespace App\Filament\Pages;

use App\Models\SchoolProfile;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationGroup = 'Pengaturan Sekolah';
    protected static ?string $navigationLabel = 'Status Deploy & Cache';
    protected static ?string $title = 'Status Deploy, Versi & Pembersihan Cache';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.system-maintenance';

    public ?string $lastCommandOutput = null;
    public ?string $lastActionName = null;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    public function getSystemInfoProperty(): array
    {
        $commitHash = '-';
        $commitMessage = '-';
        $commitDate = '-';

        try {
            if (function_exists('exec')) {
                $commitHash = trim(@exec('git rev-parse --short HEAD') ?: '-');
                $commitMessage = trim(@exec('git log -1 --pretty=%s') ?: '-');
                $commitDate = trim(@exec('git log -1 --format=%cd') ?: '-');
            }
        } catch (\Throwable $e) {
            // fallback
        }

        $profile = SchoolProfile::first();
        $hasAkreditasiCol = Schema::hasColumn('school_profiles', 'akreditasi');

        return [
            'commit_hash' => $commitHash,
            'commit_message' => $commitMessage,
            'commit_date' => $commitDate,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug') ? 'Aktif' : 'Non-Aktif',
            'has_akreditasi_column' => $hasAkreditasiCol,
            'current_akreditasi' => $profile?->akreditasi ?? 'Belum Diatur',
            'current_akreditasi_label' => $profile?->akreditasi_label ?? 'Belum Diatur',
            'db_connection' => config('database.default'),
            'db_name' => DB::connection()->getDatabaseName(),
        ];
    }

    public function clearOptimizeCache(): void
    {
        try {
            Artisan::call('optimize:clear');
            $this->lastCommandOutput = Artisan::output();
            $this->lastActionName = 'php artisan optimize:clear';

            Notification::make()
                ->title('Semua Cache Berhasil Dibersihkan! 🧹')
                ->body('Cache konfigurasi, rute, view, event, dan compiled class telah dihapus.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->lastCommandOutput = $e->getMessage();
            $this->lastActionName = 'Error optimize:clear';
            Notification::make()->title('Gagal Membersihkan Cache')->body($e->getMessage())->danger()->send();
        }
    }

    public function clearViewCache(): void
    {
        try {
            Artisan::call('view:clear');
            $out = Artisan::output();
            $this->lastCommandOutput = $out ?: 'View cache cleared successfully.';
            $this->lastActionName = 'php artisan view:clear';

            Notification::make()
                ->title('Cache Blade Template Berhasil Dihapus! ✨')
                ->body('Seluruh kompilasi view HTML dan cetakan telah disegarkan ke versi terbaru.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->lastCommandOutput = $e->getMessage();
            $this->lastActionName = 'Error view:clear';
            Notification::make()->title('Gagal Menghapus View Cache')->body($e->getMessage())->danger()->send();
        }
    }

    public function runMigration(): void
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->lastCommandOutput = Artisan::output() ?: 'Tidak ada migrasi baru (Database sudah up-to-date).';
            $this->lastActionName = 'php artisan migrate --force';

            Notification::make()
                ->title('Migrasi Database Berhasil Dijalankan! 🗄️')
                ->body('Skema tabel dan kolom terbaru telah disinkronkan ke database.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->lastCommandOutput = $e->getMessage();
            $this->lastActionName = 'Error migrate --force';
            Notification::make()->title('Gagal Menjalankan Migrasi')->body($e->getMessage())->danger()->send();
        }
    }

    public function fixAkreditasiProfile(): void
    {
        try {
            if (!Schema::hasColumn('school_profiles', 'akreditasi')) {
                Artisan::call('migrate', ['--force' => true]);
            }

            $profile = SchoolProfile::first();
            if ($profile) {
                $profile->akreditasi = 'B';
                $profile->save();
            }

            $this->lastCommandOutput = "Profil sekolah berhasil disetel dengan Akreditasi: B.\nLabel Akreditasi: " . ($profile?->akreditasi_label ?? 'B');
            $this->lastActionName = 'Sinkronisasi Akreditasi B Profil Sekolah';

            Notification::make()
                ->title('Akreditasi Berhasil Disinkronkan! 🏅')
                ->body('Nilai akreditasi sekolah di database aktif telah disetel ke Akreditasi B.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            $this->lastCommandOutput = $e->getMessage();
            $this->lastActionName = 'Error fixAkreditasiProfile';
            Notification::make()->title('Gagal Menyinkronkan Akreditasi')->body($e->getMessage())->danger()->send();
        }
    }
}
