<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoachResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CoachResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Coachs';
    protected static ?string $pluralModelLabel = 'Coachs';
    protected static ?string $slug = 'coachs';
    protected static ?string $navigationGroup = 'Utilisateurs';

    /**
     * Filtre uniquement les utilisateurs avec le rôle "coach"
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'coache');
    }

    
    /**
     * Formulaire de création/édition
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')
                ->label('Prénom')
                ->required(),

            Forms\Components\TextInput::make('last_name')
                ->label('Nom')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\TextInput::make('phone')
                ->label('Téléphone'),

            Forms\Components\FileUpload::make('photo')
                ->label('Photo')
                ->directory('photos'),

            Forms\Components\TextInput::make('password')
                ->label('Mot de passe')
                ->password()
                ->required(fn(string $context) => $context === 'create'),
            Forms\Components\Toggle::make('is_active')
                ->label('Actif ?')
                ->default(true),
        ]);
    }

    /**
     * Table de listing
     */
    public static function table(Table $table): Table
    {
        return $table

            // ->query(fn () => User::query()
            //     ->withCount('stagiaires') // 👈 pour compter les stagiaires liés
            //     ->where('role', 'coach'))
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('Prénom')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->label('Nom')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('password'),
                Tables\Columns\TextColumn::make('phone')->label('Téléphone'),

                 // ✅ Nombre de stagiaires (on ajoutera le code selon ta relation)
                Tables\Columns\TextColumn::make('stagiaires_count')
                    ->label('Nb Stagiaires')
                    ->counts('stagiaires'),
                    // ->sortable()
                    // ->badge()
                    // ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                // ✅ Statut actif / inactif
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Actif ?'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Filtrer par activité')
                    ->trueLabel('Actifs')
                    ->falseLabel('Inactifs')
                    ->placeholder('Tous'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\DeleteBulkAction::make(),
            // ]);
    }

    /**
     * Liens vers les pages CRUD
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoachs::route('/'),
            'create' => Pages\CreateCoach::route('/create'),
            'edit' => Pages\EditCoach::route('/{record}/edit'),
        ];
    }
}
