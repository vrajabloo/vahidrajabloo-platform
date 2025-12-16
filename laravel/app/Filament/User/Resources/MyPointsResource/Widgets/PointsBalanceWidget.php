<?php

namespace App\Filament\User\Resources\MyPointsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PointsBalanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('Current Points', number_format($user->points ?? 0))
                ->description('Your point balance')
                ->color('success')
                ->icon('heroicon-o-star'),
            Stat::make('Total Earned', number_format(
                $user->pointTransactions()->where('type', 'earned')->sum('points')
            ))
                ->description('All time')
                ->color('primary'),
            Stat::make('Total Spent', number_format(
                abs($user->pointTransactions()->where('type', 'spent')->sum('points'))
            ))
                ->description('All time')
                ->color('warning'),
        ];
    }
}
