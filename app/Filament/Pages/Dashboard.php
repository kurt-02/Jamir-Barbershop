<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingStats;
use App\Filament\Widgets\BarberBookingChart;
use App\Filament\Widgets\ServiceComparisonChart;
use App\Filament\Widgets\TopRatedBarbers;
use App\Filament\Widgets\LoyaltyLeaderboard;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            BookingStats::class,
            BarberBookingChart::class,
            // ServiceComparisonChart::class,
            TopRatedBarbers::class,
            LoyaltyLeaderboard::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ServiceComparisonChart::class,
        ];
    }
}
