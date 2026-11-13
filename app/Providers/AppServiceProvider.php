<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production') || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Hapus header x-amz-acl pada S3Client agar 100% kompatibel dengan Cloudflare R2
        if (config('filesystems.disks.public.driver') === 's3' || env('FILESYSTEM_DISK') === 's3') {
            try {
                foreach (['public', 's3'] as $diskName) {
                    $disk = \Illuminate\Support\Facades\Storage::disk($diskName);
                    if (method_exists($disk, 'getClient')) {
                        $client = $disk->getClient();
                        if ($client instanceof \Aws\AwsClientInterface) {
                            $client->getHandlerList()->appendInit(
                                \Aws\Middleware::mapCommand(function (\Aws\CommandInterface $command) {
                                    unset($command['ACL']);
                                    return $command;
                                }),
                                'strip-r2-acl'
                            );
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }
    }
}
