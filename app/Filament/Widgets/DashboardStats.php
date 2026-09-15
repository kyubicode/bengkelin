<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Booking', '128')
                ->description('3 pending hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Servis Selesai', '1,042')
                ->description('Bulan ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('info'),

            Stat::make('Teknisi Aktif', '8')
                ->description('Siap ditugaskan')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}