<?php

namespace App\Filament\Resources\StagiaireResource\Pages;

use App\Filament\Resources\StagiaireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStagiaires extends ListRecords
{
    protected static string $resource = StagiaireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
