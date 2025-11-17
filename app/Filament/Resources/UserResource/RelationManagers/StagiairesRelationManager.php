<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
// use Filament\Resources\Table;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class StagiairesRelationManager extends RelationManager
{
    protected static string $relationship = 'stagiairesPivot';
    protected static ?string $recordTitleAttribute = 'first_name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('Prénom'),
                Tables\Columns\TextColumn::make('last_name')->label('Nom'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('promotion'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->label('Affecter un stagiaire'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ]);
    }
}
