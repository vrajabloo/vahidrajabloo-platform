<?php

namespace App\Filament\User\Resources\MyIncomeResource\Pages;

use App\Filament\User\Resources\MyIncomeResource;
use Filament\Resources\Pages\ListRecords;

class ListMyIncomes extends ListRecords
{
    protected static string $resource = MyIncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
