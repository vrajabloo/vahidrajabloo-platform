<?php

namespace App\Filament\User\Resources\MyWalletResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WalletBalanceWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('Wallet Balance', '$' . number_format($user->wallet_balance ?? 0, 2))
                ->description('Current balance')
                ->color('success')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Total Deposits', '$' . number_format(
                $user->walletTransactions()->where('type', 'deposit')->where('status', 'completed')->sum('amount'),
                2
            ))
                ->description('All time')
                ->color('primary'),
            Stat::make('Total Withdrawals', '$' . number_format(
                $user->walletTransactions()->where('type', 'withdraw')->where('status', 'completed')->sum('amount'),
                2
            ))
                ->description('All time')
                ->color('warning'),
        ];
    }
}
