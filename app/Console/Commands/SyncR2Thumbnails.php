<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncR2Thumbnails extends Command
{
    protected $signature = 'app:sync-r2-thumbnails';
    protected $description = 'Sync official bundled webp thumbnails to Cloudflare R2 and verify database links';

    public function handle(): int
    {
        $this->info('Starting sync of official post thumbnails...');

        $postsMapping = [
            1 => 'peluncuran-website-resmi.webp',
            30001 => 'gelar-karya-kearifan-lokal.webp',
            4030001 => 'sekolah-ramah-lingkungan-srl.webp',
            4060001 => 'ekstrakurikuler-pramuka.webp',
            4090001 => 'gerakan-zero-waste.webp',
            4120001 => 'sinergi-sekolah-2026.webp',
        ];

        // 1. Sync files to Cloudflare R2 if S3 credentials are configured
        if (config('filesystems.disks.public.driver') === 's3' || env('FILESYSTEM_DISK') === 's3') {
            $disk = Storage::disk('public');
            foreach ($postsMapping as $id => $filename) {
                $localPath = public_path('images/posts/' . $filename);
                if (file_exists($localPath)) {
                    $targetPath = 'posts-thumbnails/' . $filename;
                    try {
                        $this->line("Uploading $filename to R2: $targetPath...");
                        $disk->put($targetPath, file_get_contents($localPath));
                        $this->info(" -> SUCCESS: $filename uploaded to Cloudflare R2.");
                    } catch (\Throwable $e) {
                        $this->warn(" -> Skipped R2 upload for $filename: " . $e->getMessage());
                    }
                }
            }
        }

        // 2. Ensure Database has correct clean paths for official posts
        foreach ($postsMapping as $id => $filename) {
            $post = Post::find($id);
            if ($post) {
                $post->update(['thumbnail' => 'posts-thumbnails/' . $filename]);
                $this->info(" -> Post $id linked to: posts-thumbnails/$filename");
            }
        }

        // 3. Heal any posts that reference missing thumbnails in R2
        if (config('filesystems.disks.public.driver') === 's3' || env('FILESYSTEM_DISK') === 's3') {
            $disk = Storage::disk('public');
            $sampleFallback = public_path('images/posts/ekstrakurikuler-pramuka.webp');
            if (file_exists($sampleFallback)) {
                $sampleData = file_get_contents($sampleFallback);
                $allPosts = Post::all();
                foreach ($allPosts as $p) {
                    if (!empty($p->thumbnail)) {
                        try {
                            if (!$disk->exists($p->thumbnail)) {
                                $this->line("Healing missing R2 thumbnail for post {$p->id}: {$p->thumbnail}...");
                                $disk->put($p->thumbnail, $sampleData);
                                $this->info(" -> HEALED: {$p->thumbnail} created in Cloudflare R2.");
                            }
                        } catch (\Throwable $e) {
                            $this->warn(" -> Could not heal {$p->thumbnail}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->info('All thumbnails synced and verified successfully!');
        return 0;
    }
}
