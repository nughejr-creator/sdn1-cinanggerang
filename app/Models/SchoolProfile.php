<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolProfile extends Model
{
    protected $guarded = ['id'];

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

