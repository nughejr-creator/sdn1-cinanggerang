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
        if (!empty($path)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
            } catch (\Throwable $e) {
                return asset('storage/' . $path);
            }
        }
        return null;
    }
}
