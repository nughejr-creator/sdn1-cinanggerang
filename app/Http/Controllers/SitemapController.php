<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Hasilkan Peta Situs XML Dinamis untuk Pengindeksan Mesin Pencari Google
     */
    public function index(): Response
    {
        $urls = [];

        // 1. Halaman Statis & Menu Utama Portal Sekolah
        $corePages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('profil'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('warta'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('pengumuman'), 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->toAtomString()],
            ['url' => route('perangkat-ajar'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('guru'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('rombel'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->startOfMonth()->toAtomString()],
            ['url' => route('siswa'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->startOfMonth()->toAtomString()],
            ['url' => route('dokumentasi'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('ppdb'), 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('ppdb.register'), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->startOfWeek()->toAtomString()],
            ['url' => route('pkks'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->startOfMonth()->toAtomString()],
        ];

        foreach ($corePages as $page) {
            $urls[] = $page;
        }

        // 2. Konten Dinamis: Seluruh Artikel Warta & Berita Resmi
        $posts = Post::where('status', 'Publish')
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $urls[] = [
                'url' => route('warta.detail', $post->slug ?: $post->id),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
            ];
        }

        // 3. Konten Dinamis: Seluruh Pengumuman Kedinasan Aktif
        $announcements = Announcement::where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($announcements as $ann) {
            $urls[] = [
                'url' => route('pengumuman.detail', $ann->id),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => ($ann->updated_at ?? now())->toAtomString(),
            ];
        }

        $xml = view('seo.sitemap', compact('urls'))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    /**
     * File Petunjuk Perayap (Robots.txt) Dinamis
     */
    public function robots(): Response
    {
        $baseUrl = rtrim(config('app.url') ?: url('/'), '/');
        $sitemapUrl = $baseUrl . '/sitemap.xml';

        $content = "User-agent: *\n"
            . "Allow: /\n"
            . "Disallow: /admin/\n"
            . "Disallow: /admin\n"
            . "Disallow: /livewire/\n"
            . "Disallow: /presensi\n\n"
            . "Sitemap: {$sitemapUrl}\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
