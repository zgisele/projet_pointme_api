<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StagiaireResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StagiaireResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Stagiaires';
    protected static ?string $pluralModelLabel = 'Stagiaires';
    protected static ?string $slug = 'stagiaires';
    protected static ?string $navigationGroup = 'Utilisateurs';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'stagiaire');
    }
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->label('Prénom')->required(),
            Forms\Components\TextInput::make('last_name')->label('Nom')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('password')->label('mot de passe'),
            Forms\Components\TextInput::make('phone')->label('Téléphone'),
            Forms\Components\FileUpload::make('photo')
            ->disk('public')
            ->directory('photos')
            ->label('Photo'),
            // Forms\Components\FileUpload::make('photo')->directory('photos')->label('Photo'),
            Forms\Components\TextInput::make('promotion')->label('Promotion'),
            Forms\Components\DatePicker::make('start_date')->label('Début'),
            Forms\Components\DatePicker::make('end_date')->label('Fin'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('Prénom')->searchable(),
                Tables\Columns\TextColumn::make('last_name')->label('Nom')->searchable(),
                // Tables\Columns\TextColumn::make('password'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone')->label('Téléphone'),
                Tables\Columns\TextColumn::make('promotion'),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStagiaires::route('/'),
            'create' => Pages\CreateStagiaire::route('/create'),
            'edit' => Pages\EditStagiaire::route('/{record}/edit'),
        ];
    }
}
