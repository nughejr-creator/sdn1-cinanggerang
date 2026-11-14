<?php

namespace App\Services;

use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VisitorCounterService
{
    /**
     * Catat kunjungan pengunjung unik
     */
    public static function recordVisit(Request $request): void
    {
        // Abaikan request bukan GET
        if (!$request->isMethod('GET')) {
            return;
        }

        // Abaikan rute admin, livewire, api, storage, atau debug
        if ($request->is('admin*', 'livewire*', 'api*', 'storage*', 'debug*', '_debugbar*')) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        // Abaikan bot dan web crawler pencari
        if (self::isBot($userAgent)) {
            return;
        }

        try {
            $ip = $request->ip() ?: '127.0.0.1';
            $salt = config('app.key') ?: 'sdn1cinanggerang';
            $ipHash = hash('sha256', $ip . $salt);
            $today = today();

            $log = VisitorLog::firstOrNew([
                'ip_hash' => $ipHash,
                'visited_date' => $today,
            ]);

            if (!$log->exists) {
                $log->hits = 1;
                $log->user_agent = substr($userAgent, 0, 250);
            } else {
                $log->hits = ($log->hits ?? 1) + 1;
            }

            $log->page_url = substr($request->path(), 0, 250);
            $log->last_activity_at = now();
            $log->save();
        } catch (\Throwable $e) {
            // Hindari memutus alur pengguna jika terjadi kegagalan basis data sekunder
            \Illuminate\Support\Facades\Log::warning('VisitorCounter error: ' . $e->getMessage());
        }
    }

    /**
     * Ambil statistik pengunjung (Cache 60 detik agar sangat cepat)
     */
    public static function getStats(): array
    {
        return Cache::remember('visitor_counter_stats', 60, function () {
            $now = now();
            $today = today();
            $yesterday = today()->subDay();

            $online = VisitorLog::where('last_activity_at', '>=', now()->subMinutes(5))->count();
            $todayVisitors = VisitorLog::whereDate('visited_date', $today)->count();
            $yesterdayVisitors = VisitorLog::whereDate('visited_date', $yesterday)->count();
            $monthVisitors = VisitorLog::whereMonth('visited_date', $now->month)
                ->whereYear('visited_date', $now->year)
                ->count();
            $totalVisitors = VisitorLog::count();
            $totalHits = (int) VisitorLog::sum('hits');

            $offset = (int) env('VISITOR_COUNTER_OFFSET', 250);

            return [
                'online' => max(1, $online),
                'today' => max(1, $todayVisitors),
                'yesterday' => $yesterdayVisitors,
                'month' => max(1, $monthVisitors),
                'total' => $totalVisitors + $offset,
                'total_hits' => $totalHits + ($offset * 3),
            ];
        });
    }

    /**
     * Cek apakah user agent tergolong bot / crawler
     */
    protected static function isBot(string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $botPatterns = [
            'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'googlebot',
            'bingbot', 'yandexbot', 'baiduspider', 'duckduckbot', 'semrushbot',
            'ahrefsbot', 'dotbot', 'mj12bot', 'facebookexternalhit', 'whatsapp',
        ];

        $ua = strtolower($userAgent);
        foreach ($botPatterns as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
