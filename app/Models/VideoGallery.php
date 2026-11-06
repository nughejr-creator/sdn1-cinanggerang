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

        // 3. TikTok
        if ($this->platform === 'TikTok' || str_contains($url, 'tiktok.com')) {
            if (preg_match('/video\/(\d+)/', $url, $matches)) {
                $videoId = $matches[1];
                return '<div class="w-full flex flex-col items-center justify-center p-2">' .
                    '<iframe src="https://www.tiktok.com/player/v1/' . $videoId . '?autoplay=1" class="w-full max-w-[340px] h-[540px] rounded-2xl border border-forest-800 shadow-2xl bg-black" allow="autoplay; fullscreen" allowfullscreen></iframe>' .
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
}
