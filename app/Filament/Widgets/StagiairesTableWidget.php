<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StagiairesTableWidget extends TableWidget
{
    protected static ?string $heading = 'Liste des Stagiaires';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role', 'stagiaire')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone'),

                Tables\Columns\TextColumn::make('promotion')
                    ->label('Promotion'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Début')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fin')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}



// <?php

// namespace App\Filament\Widgets;

// use Filament\Tables;
// use Filament\Tables\Table;
// use Filament\Widgets\TableWidget as BaseWidget;
// use App\Models\User;


// class StagiairesTableWidget extends BaseWidget
// {
//     protected static ?string $heading = 'Liste des Stagiaires';

//     protected int | string | array $columnSpan = 'full';
//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(
//                 // ...
//                 User::query()->where('role', 'stagiaire')
//             )
//             ->columns([
//                 // ...
//                 Tables\Columns\TextColumn::make('name')->label('Nom'),
//                 Tables\Columns\TextColumn::make('email')->label('Email'),
//                 Tables\Columns\TextColumn::make('created_at')->label('Inscrit le')->date(),
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//                 Tables\Actions\DeleteAction::make(),
//             ]);
//     }
// }
