<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoGallery extends Model
{
    protected $table = 'video_galleries';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'is_featured' => 'boolean',
    ];

    /**
     * Konversi URL ke Embed HTML untuk YouTube, TikTok, dan Google Drive
     */
    public function getEmbedHtmlAttribute(): ?string
    {
        $url = $this->video_url;

        // 1. YouTube
        if ($this->platform === 'YouTube' || str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            $videoId = null;
            if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $url, $matches)) {
                $videoId = $matches[1];
            }
            if ($videoId) {
                return '<div class="aspect-video w-full"><iframe class="w-full h-full rounded-lg" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }
        }

        // 2. Google Drive
        if ($this->platform === 'Google Drive' || str_contains($url, 'drive.google.com')) {
            if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                $fileId = $matches[1];
                return '<div class="aspect-video w-full"><iframe class="w-full h-full rounded-lg" src="https://drive.google.com/file/d/' . $fileId . '/preview" frameborder="0"></iframe></div>';
            }
        }

        // 3. TikTok (Official Player v1)
        if ($this->platform === 'TikTok' || str_contains($url, 'tiktok.com')) {
            $videoId = '';
            if (preg_match('/(?:video\/|embed\/v2\/|player\/v1\/)(\d+)/', $url, $matches)) {
                $videoId = $matches[1];
            }
            if ($videoId) {
                return '<div class="w-full flex flex-col items-center justify-center p-2">' .
                    '<iframe height="578" width="325" src="https://www.tiktok.com/player/v1/' . $videoId . '?embed_from=webapp_share&music_info=1&description=1&autoplay=1&muted=1" class="w-[325px] h-[578px] max-w-full rounded-2xl shadow-2xl border-0 bg-black" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; fullscreen; gyroscope; picture-in-picture; web-share" title="' . htmlspecialchars($this->judul ?? 'Video Dokumentasi') . '" allowfullscreen loading="lazy"></iframe>' .
                    '<div class="mt-3 text-center">' .
                        '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#FE2C55] hover:bg-[#e0264a] text-white rounded-xl text-xs font-bold shadow-md transition">' .
                            '<span>Buka di Aplikasi TikTok @sdn.cinanggerang1</span>' .
                            '<span>&rarr;</span>' .
                        '</a>' .
                    '</div>' .
                '</div>';
            }
        }

        return '<a href="' . htmlspecialchars($url) . '" target="_blank" class="text-primary-600 underline">Tonton Video di Tautan Eksternal</a>';
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!empty($this->thumbnail_custom)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->thumbnail_custom);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->thumbnail_custom);
            }
        }
        return null;
    }
}
