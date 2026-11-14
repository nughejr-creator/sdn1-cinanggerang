<?php

namespace App\Filament\Widgets;

use App\Services\VisitorCounterService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $stats = VisitorCounterService::getStats();

        return [
            Stat::make('Pengunjung Hari Ini', number_format($stats['today'], 0, ',', '.'))
                ->description("Kemarin: " . number_format($stats['yesterday'], 0, ',', '.') . " pengunjung")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Sedang Online', number_format($stats['online'], 0, ',', '.') . ' Orang')
                ->description('Aktif dalam 5 menit terakhir')
                ->descriptionIcon('heroicon-m-signal')
                ->color('emerald'),

            Stat::make('Pengunjung Bulan Ini', number_format($stats['month'], 0, ',', '.'))
                ->description('Akumulasi ' . now()->locale('id')->isoFormat('MMMM Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Total Pengunjung & Hits', number_format($stats['total'], 0, ',', '.'))
                ->description(number_format($stats['total_hits'], 0, ',', '.') . ' Total tayangan halaman')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),
        ];
    }
}
