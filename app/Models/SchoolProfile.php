<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolProfile extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_ticker_active' => 'boolean',
    ];

    /**
     * Label Akreditasi Resmi (Misal: 'B (Baik)', 'A (Unggul)', dsb.)
     */
    public function getAkreditasiLabelAttribute(): string
    {
        $val = strtoupper(trim($this->akreditasi ?: 'B'));
        if ($val === 'A') return 'A (Unggul)';
        if ($val === 'B') return 'B (Baik)';
        if ($val === 'C') return 'C (Cukup)';
        return $this->akreditasi ?: 'B';
    }

    /**
     * Teks pengumuman berjalan (ticker) dengan fallback default
     */
    public function getTickerTextDisplayAttribute(): string
    {
        return !empty($this->ticker_text)
            ? $this->ticker_text
            : 'Pelaksanaan Sumatif Akhir Semester (SAS) 1 T.A. 2026/2027 SD Negeri Cinanggerang I dijadwalkan mulai 30 November s.d. 12 Desember 2026. Mohon dipersiapkan dengan optimal. • Pendaftaran PPDB Tahun Ajaran 2026/2027 Resmi Dibuka Melalui Jalur Online. • Jam Efektif Presensi GTK Dimulai Pukul 06:30 - 07:30 WIB.';
    }

    /**
     * URL Logo Resmi Sekolah (Cloudflare R2 / Local Storage dengan Fallback)
     */
    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo)) {
            try {
                return Storage::disk('public')->url($this->logo);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->logo);
            }
        }
        return asset('images/logo-official.png');
    }

    /**
     * URL Logo Tut Wuri Handayani
     */
    public function getLogoTutwuriUrlAttribute(): string
    {
        if (!empty($this->logo_tutwuri)) {
            try {
                return Storage::disk('public')->url($this->logo_tutwuri);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->logo_tutwuri);
            }
        }
        return 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';
    }

    /**
     * URL Logo Pemda (Kab. Sumedang)
     */
    public function getLogoPemdaUrlAttribute(): string
    {
        if (!empty($this->logo_pemda)) {
            try {
                return Storage::disk('public')->url($this->logo_pemda);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->logo_pemda);
            }
        }
        return 'https://upload.wikimedia.org/wikipedia/commons/5/52/Lambang_Kabupaten_Sumedang.png';
    }

    /**
     * URL Favicon Browser
     */
    public function getFaviconUrlAttribute(): string
    {
        if (!empty($this->favicon)) {
            try {
                return Storage::disk('public')->url($this->favicon);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->favicon);
            }
        }
        return $this->logo_url;
    }

    /**
     * URL Foto Resmi Kepala Sekolah
     */
    public function getFotoKepalaSekolahUrlAttribute(): ?string
    {
        if (!empty($this->foto_kepala_sekolah)) {
            try {
                return Storage::disk('public')->url($this->foto_kepala_sekolah);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->foto_kepala_sekolah);
            }
        }
        return null;
    }

    /**
     * URL Banner Hero / Foto Gedung
     */
    public function getBannerHeroUrlAttribute(): ?string
    {
        $image = $this->banner_hero ?? $this->foto_bangunan;
        if (!empty($image)) {
            try {
                return Storage::disk('public')->url($image);
            } catch (\Throwable $e) {
                return asset('storage/' . $image);
            }
        }
        return null;
    }
}

