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
            $basename = basename($this->thumbnail);
            if (file_exists(public_path('images/posts/' . $basename))) {
                return asset('images/posts/' . $basename);
            }

            if (file_exists(public_path('storage/' . $this->thumbnail))) {
                return asset('storage/' . $this->thumbnail);
            }

            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->thumbnail);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->thumbnail);
            }
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
