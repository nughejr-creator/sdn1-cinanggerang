<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeadmasterProfile extends Model
{
    use HasFactory;

    protected $table = 'headmaster_profiles';

    protected $guarded = ['id'];

    protected $casts = [
        'appointment_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Riwayat Diklat, Prestasi, Organisasi, dan Pendidikan
     */
    public function histories(): HasMany
    {
        return $this->hasMany(HeadmasterHistory::class)->orderBy('sort_order')->orderByDesc('year');
    }

    public function trainings(): HasMany
    {
        return $this->histories()->where('type', 'training');
    }

    public function achievements(): HasMany
    {
        return $this->histories()->where('type', 'achievement');
    }

    public function organizations(): HasMany
    {
        return $this->histories()->where('type', 'organization');
    }

    public function educations(): HasMany
    {
        return $this->histories()->where('type', 'education');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $path = $this->photo_path ?? $this->photo;

        // Jika path diisi berkas nyata yang bukan seeder bawaan 'headmaster/aep-yayat.png'
        if (!empty($path) && $path !== 'headmaster/aep-yayat.png') {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
            } catch (\Throwable $e) {
                return asset('storage/' . $path);
            }
        }

        // Fallback 1: Ambil dari Foto Resmi Kepala Sekolah di Profil Sekolah
        try {
            $schoolProfile = SchoolProfile::first();
            if (!empty($schoolProfile?->foto_kepala_sekolah_url)) {
                return $schoolProfile->foto_kepala_sekolah_url;
            }
        } catch (\Throwable $e) {}

        // Fallback 2: Ambil dari Data GTK Guru (Bpk. Aep Yayat, S.Pd.SD)
        try {
            $teacher = Teacher::where('jabatan', 'like', '%Kepala%')
                ->orWhere('nama', 'like', '%Aep Yayat%')
                ->first();
            if (!empty($teacher?->foto_url)) {
                return $teacher->foto_url;
            }
        } catch (\Throwable $e) {}

        return null;
    }
}
