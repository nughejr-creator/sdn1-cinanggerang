<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileUrlAttribute(): ?string
    {
        if (empty($this->file_dokumen)) {
            return null;
        }

        try {
            return Storage::disk('public')->url($this->file_dokumen);
        } catch (\Throwable $e) {
            return asset('storage/' . $this->file_dokumen);
        }
    }
}
