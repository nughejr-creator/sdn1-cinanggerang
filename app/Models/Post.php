<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'date',
        'tampil_di_beranda' => 'boolean',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        // 1. Cek langsung jika berkas fisik terdaftar di aset publik images/posts/
        if (!empty($this->thumbnail)) {
            $basename = basename($this->thumbnail);
            if (file_exists(public_path('images/posts/' . $basename))) {
                return asset('images/posts/' . $basename);
            }
        }

        // 2. Pemetaan cerdas cadangan (fallback) untuk 6 artikel utama sekolah
        // Berfungsi otomatis jika thumbnail belum diisi atau masih mengarah ke template bawaan
        $officialPostImages = [
            1 => 'peluncuran-website-resmi.webp',
            30001 => 'gelar-karya-kearifan-lokal.webp',
            4030001 => 'sekolah-ramah-lingkungan-srl.webp',
            4060001 => 'ekstrakurikuler-pramuka.webp',
            4090001 => 'gerakan-zero-waste.webp',
            4120001 => 'sinergi-sekolah-2026.webp',
        ];

        if (empty($this->thumbnail) || (isset($officialPostImages[$this->id]) && basename($this->thumbnail) === $officialPostImages[$this->id])) {
            if (isset($officialPostImages[$this->id]) && file_exists(public_path('images/posts/' . $officialPostImages[$this->id]))) {
                return asset('images/posts/' . $officialPostImages[$this->id]);
            }
        }

        // 3. Jika pengguna mengunggah foto kustom baru (Cloudflare R2 / Storage Publik)
        if (!empty($this->thumbnail)) {
            if (file_exists(public_path('storage/' . $this->thumbnail))) {
                return asset('storage/' . $this->thumbnail);
            }

            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->thumbnail);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->thumbnail);
            }
        }

        // 4. Cadangan terakhir jika berkas kustom tidak ditemukan
        if (isset($officialPostImages[$this->id]) && file_exists(public_path('images/posts/' . $officialPostImages[$this->id]))) {
            return asset('images/posts/' . $officialPostImages[$this->id]);
        }

        return null;
    }

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SchoolProgram::class, 'program_id');
    }

    public function getDetailUrlAttribute(): string
    {
        return route('warta.detail', $this->slug ?: $this->id);
    }
}
