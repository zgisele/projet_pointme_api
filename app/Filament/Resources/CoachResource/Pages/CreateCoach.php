<?php

namespace App\Filament\Resources\CoachResource\Pages;

use App\Filament\Resources\CoachResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoach extends CreateRecord
{
    protected static string $resource = CoachResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'coache'; // 👈 Ajoute automatiquement le rôle coach
        return $data;
    }

    public function getTitle(): string
{
    return 'Créer un coach';
}
}
