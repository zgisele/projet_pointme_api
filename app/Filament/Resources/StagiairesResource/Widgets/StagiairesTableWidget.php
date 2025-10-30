<?php

namespace App\Filament\Resources\StagiairesResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StagiairesTableWidget extends BaseWidget
{

    protected static ?string $heading = 'Liste des Stagiaires';

    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
                User::query()->where('role', 'stagiaire')
            )
            ->columns([
                // ...
                Tables\Columns\TextColumn::make('name')->label('Nom'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('created_at')->label('Inscrit le')->date(),
            ])
            -> actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
