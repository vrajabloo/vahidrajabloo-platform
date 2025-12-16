<?php

namespace App\Filament\User\Resources\MyProjectResource\Pages;

use App\Filament\User\Resources\MyProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListMyProjects extends ListRecords
{
    protected static string $resource = MyProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
