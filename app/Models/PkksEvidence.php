<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PkksEvidence extends Model
{
    use HasFactory;

    protected $table = 'pkks_evidences';

    protected $guarded = ['id'];

    protected $casts = [
        'is_public' => 'boolean',
        'download_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(PkksIndicator::class, 'pkks_indicator_id');
    }

    /**
     * Helper untuk embed link (YouTube / Google Drive / TikTok)
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (empty($this->external_url)) {
            return null;
        }

        $url = $this->external_url;

        // YouTube embed
        if (str_contains($url, 'youtube.com/watch') || str_contains($url, 'youtu.be/')) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
            if (!empty($matches[1])) {
                return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0';
            }
        }

        // Google Drive embed preview
        if (str_contains($url, 'drive.google.com/file/d/')) {
            return preg_replace('/\/view.*$/', '/preview', $url);
        }

        return $url;
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!empty($this->file_path)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_path);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->file_path);
            }
        }
        return null;
    }
}
