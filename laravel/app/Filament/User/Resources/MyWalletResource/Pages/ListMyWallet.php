<?php

namespace App\Filament\User\Resources\MyWalletResource\Pages;

use App\Filament\User\Resources\MyWalletResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMyWallet extends ListRecords
{
    protected static string $resource = MyWalletResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\User\Resources\MyWalletResource\Widgets\WalletBalanceWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
