<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            (new \Database\Seeders\VideoGallerySeeder())->run();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal auto-seed galeri video baru: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu menghapus video
    }
};
