<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'foto' => 'array',
    ];

    /**
     * Dapatkan daftar URL lengkap untuk seluruh foto dokumentasi kegiatan
     */
    public function getFotoUrlsAttribute(): array
    {
        if (empty($this->foto) || !is_array($this->foto)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($path) {
            if (empty($path)) return null;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            try {
                return Storage::disk('public')->url($path);
            } catch (\Throwable $e) {
                return asset('storage/' . $path);
            }
        }, $this->foto)));
    }

    /**
     * Cover foto utama kegiatan
     */
    public function getCoverUrlAttribute(): ?string
    {
        $urls = $this->foto_urls;
        return !empty($urls) ? $urls[0] : null;
    }

    /**
     * Jumlah foto kegiatan
     */
    public function getJumlahFotoAttribute(): int
    {
        return is_array($this->foto) ? count($this->foto) : 0;
    }
}
