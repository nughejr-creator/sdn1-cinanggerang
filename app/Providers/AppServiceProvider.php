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

        // Matikan fetch file information otomatis agar FilePond di Cloudflare R2 / S3
        // tidak tersangkut / hanging 'Menunggu ukuran berkas'
        FileUpload::configureUsing(function (FileUpload $fileUpload): void {
            $fileUpload->fetchFileInformation(false);
        });
    }
}
