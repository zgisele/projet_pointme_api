<?php

namespace App\Filament\Resources\StagiaireResource\Pages;

use App\Filament\Resources\StagiaireResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStagiaire extends CreateRecord
{
    protected static string $resource = StagiaireResource::class;

     protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'stagiaire'; // 👈 Ajoute automatiquement le rôle stagiaire
        return $data;
    }
}
