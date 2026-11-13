<?php

namespace App\Console\Commands;

use App\Models\TeacherAttendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class HealTeacherAttendances extends Command
{
    protected $signature = 'app:heal-attendances';
    protected $description = 'Heal missing teacher attendance photos in Cloudflare R2 using teacher profile photos or avatar';

    public function handle(): int
    {
        $this->info('Checking teacher attendance photos in Cloudflare R2...');

        if (config('filesystems.disks.public.driver') !== 's3' && env('FILESYSTEM_DISK') !== 's3') {
            $this->warn('S3 / Cloudflare R2 is not active as public disk. Skipping.');
            return 0;
        }

        $disk = Storage::disk('public');
        $attendances = TeacherAttendance::with('teacher')
            ->whereNotNull('foto_bukti')
            ->orWhereNotNull('foto_pulang')
            ->get();

        $healedCount = 0;

        foreach ($attendances as $a) {
            // 1. Cek foto_bukti (masuk)
            if (!empty($a->foto_bukti)) {
                try {
                    if (!$disk->exists($a->foto_bukti)) {
                        $this->line("Missing foto_bukti for attendance {$a->id} ({$a->teacher?->nama}): {$a->foto_bukti}");
                        $sourceContent = null;

                        if ($a->teacher && !empty($a->teacher->foto) && $disk->exists($a->teacher->foto)) {
                            $sourceContent = $disk->get($a->teacher->foto);
                        } else {
                            $defaultPath = public_path('images/logo-official.png');
                            if (file_exists($defaultPath)) {
                                $sourceContent = file_get_contents($defaultPath);
                            }
                        }

                        if ($sourceContent) {
                            $disk->put($a->foto_bukti, $sourceContent);
                            $this->info(" -> HEALED: {$a->foto_bukti}");
                            $healedCount++;
                        }
                    }
                } catch (\Throwable $e) {
                    $this->warn("Error checking {$a->foto_bukti}: " . $e->getMessage());
                }
            }

            // 2. Cek foto_pulang
            if (!empty($a->foto_pulang)) {
                try {
                    if (!$disk->exists($a->foto_pulang)) {
                        $this->line("Missing foto_pulang for attendance {$a->id} ({$a->teacher?->nama}): {$a->foto_pulang}");
                        $sourceContent = null;

                        if ($a->teacher && !empty($a->teacher->foto) && $disk->exists($a->teacher->foto)) {
                            $sourceContent = $disk->get($a->teacher->foto);
                        } else {
                            $defaultPath = public_path('images/logo-official.png');
                            if (file_exists($defaultPath)) {
                                $sourceContent = file_get_contents($defaultPath);
                            }
                        }

                        if ($sourceContent) {
                            $disk->put($a->foto_pulang, $sourceContent);
                            $this->info(" -> HEALED PULANG: {$a->foto_pulang}");
                            $healedCount++;
                        }
                    }
                } catch (\Throwable $e) {
                    $this->warn("Error checking {$a->foto_pulang}: " . $e->getMessage());
                }
            }
        }

        $this->info("Healed {$healedCount} attendance photos successfully!");
        return 0;
    }
}
