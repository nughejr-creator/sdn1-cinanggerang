<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolProgram extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'program_id');
    }

    /**
     * Label Kategori yang Rapi untuk Tampilan
     */
    public function getCategoryLabelAttribute(): string
    {
        return match (strtolower($this->category)) {
            'ekstrakurikuler' => 'Ekstrakurikuler',
            'kokurikuler' => 'Kokurikuler',
            'pembiasaan' => 'Pembiasaan',
            default => ucfirst($this->category),
        };
    }

    /**
     * Warna Badge Representatif
     */
    public function getBadgeColorAttribute(): string
    {
        return match (strtolower($this->category)) {
            'ekstrakurikuler' => 'amber',
            'kokurikuler' => 'success',
            'pembiasaan' => 'info',
            default => 'gray',
        };
    }
}
