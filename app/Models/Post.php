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
        if (!empty($this->thumbnail)) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }
            try {
                $url = \Illuminate\Support\Facades\Storage::disk('public')->url($this->thumbnail);
                if (!str_contains($url, 'r2.dev')) {
                    return $url;
                }
            } catch (\Throwable $e) {}

            return asset('storage/' . ltrim($this->thumbnail, '/'));
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
