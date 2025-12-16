<?php

namespace App\Filament\User\Resources\MyPointsResource\Pages;

use App\Filament\User\Resources\MyPointsResource;
use Filament\Resources\Pages\ListRecords;

class ListMyPoints extends ListRecords
{
    protected static string $resource = MyPointsResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\User\Resources\MyPointsResource\Widgets\PointsBalanceWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
