<?php

namespace App\Filament\Widgets;

use App\Models\eBooks;
use App\Models\User;
use Carbon\Carbon;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use Filament\Support\Enums\IconPosition;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Stat as Card;

class AdvancedDashStatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        //For User Widget
        // Set how many months back you want to track, e.g., last 6 months
        $monthsBack = 6;

        // Get user count grouped by month and year
        $userGrowth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->where('status', '=', 'active')
            ->where('created_at', '>=', Carbon::now()->subMonths($monthsBack))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Create an array for the last X months with user growth data
        $months = collect(range(0, $monthsBack - 1))->map(function ($month) use ($userGrowth) {
            $date = Carbon::now()->subMonths($month)->format('Y-m');
            return $userGrowth[$date] ?? 0; // If no users in a month, default to 0
        })->reverse()->values()->toArray();


        //For Books Widget
        // Set how many months back you want to track, e.g., last 6 months
        $monthsBack = 6;

        // Get user count grouped by month and year
        $userGrowth = eBooks::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths($monthsBack))
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Create an array for the last X months with user growth data
        $months = collect(range(0, $monthsBack - 1))->map(function ($month) use ($userGrowth) {
            $date = Carbon::now()->subMonths($month)->format('Y-m');
            return $userGrowth[$date] ?? 0; // If no users in a month, default to 0
        })->reverse()->values()->toArray();

        return [
            Stat::make('Users', User::where('status', '=', 'active')->count())
                ->description('Registered Users')
                ->descriptionIcon('heroicon-o-check-circle', 'before')
                ->icon('heroicon-m-user-group')
                ->chartColor('success')
                ->iconPosition('end')
                ->color('success')
                // ->backgroundColor('info')
                ->iconBackgroundColor('danger')
                ->iconColor('success'),

                Stat::make('EBooks', eBooks::count())
                ->icon('heroicon-m-book-open')
                ->iconBackgroundColor('danger')
                    ->iconColor('info')
                    ->description('Number of eBooks')
                    ->descriptionIcon('heroicon-o-document-check', 'before')
                    ->descriptionColor('success')


        ];
    }
}
