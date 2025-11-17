<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;





class RecapitulatifCoachs extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $title = 'Récapitulatif des Coachs';
    protected static ?string $navigationLabel = 'Récap utilisateur';
    // protected static ?string $navigationGroup = 'Gestion utilisateurs';
    protected static ?string $slug = 'recapitulatif-coachs'; // route: /admin/recapitulatif-coachs
    protected static string $view = 'filament.pages.recapitulatif-coachs';



    protected function getTableQuery(): Builder
    {
        return User::query()
            ->where('role', 'coache')
            ->with('stagiairesPivot');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('full_name')
                ->label('Coach')
                ->getStateUsing(fn(User $record) => $record->first_name . ' ' . $record->last_name),

            Tables\Columns\TextColumn::make('stagiaires_count')
                ->label('Nombre de stagiaires')
                ->getStateUsing(fn(User $record) => $record->stagiairesPivot->count()),
            Tables\Columns\TextColumn::make('stagiaires_list')
                ->label('Liste des stagiaires')
                ->formatStateUsing(fn($state, User $record) =>
                    $record->stagiairesPivot->map(fn($stagiaire) =>
                        '<a href="'.route('filament.resources.users.edit', $stagiaire->id).'">'.$stagiaire->first_name.' '.$stagiaire->last_name.'</a>'
                    )->implode(', ')
                )
                ->html(),

            // Tables\Columns\TextColumn::make('action')
            // ->label('Actions')
            // ->getStateUsing(fn() => '')
            // ->actions([
            //     Action::make('affecter')
            //         ->label('Affecter un stagiaire')
            //         ->form([
            //             Forms\Components\Select::make('stagiaire_id')
            //                 ->label('Stagiaire')
            //                 ->options(fn() => User::where('role', 'stagiaire')->pluck('first_name','id')),
            //             Forms\Components\DatePicker::make('date_affectation')
            //                 ->label('Date d’affectation')
            //                 ->default(now()),
            //         ])
            //         ->action(function (array $data, User $record) {
            //             $record->stagiairesPivot()->attach($data['stagiaire_id'], ['date_affectation' => $data['date_affectation']]);
            //         })
            // ]),
    




            Tables\Columns\TextColumn::make('dernier_pointage')
                ->label('Dernière activité')
                ->getStateUsing(fn(User $record) =>
                    $record->stagiairesPivot->sortByDesc('updated_at')->first()?->updated_at?->diffForHumans()
                ),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('promotion')
                ->label('Promotion')
                ->options(fn() => User::where('role', 'stagiaire')->pluck('promotion', 'promotion')->unique()),
            SelectFilter::make('coach')
                ->label('Coach')
                ->options(fn() => User::where('role', 'coach')->get()
                ->mapWithKeys(fn($coach) => [$coach->id => $coach->first_name . ' ' . $coach->last_name])),

                
//                 pluck($coach->first_name . ' ' . $coach->last_name
// , 'id')),
            Filter::make('date_affectation')
                ->label('Période d’affectation')
                ->form([
                    DatePicker::make('start')->label('Début'),
                    DatePicker::make('end')->label('Fin'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['start'])) {
                        $query->whereHas('stagiairesPivot', fn($q) =>
                            $q->wherePivot('date_affectation', '>=', $data['start'])
                        );
                    }
                    if (!empty($data['end'])) {
                        $query->whereHas('stagiairesPivot', fn($q) =>
                            $q->wherePivot('date_affectation', '<=', $data['end'])
                        );
                    }
                }),
        ];
    }

    // Les actions de chaque ligne
protected function getTableActions(): array
{
    return [
        Action::make('affecter')
            ->label('Affecter un stagiaire')
            ->form([
                Forms\Components\Select::make('stagiaire_id')
                    ->label('Stagiaire')
                    ->options(fn() => User::where('role', 'stagiaire')->pluck('first_name', 'id')),
                Forms\Components\DatePicker::make('date_affectation')
                    ->label('Date d’affectation')
                    ->default(now()),
            ])
            ->action(function (array $data, User $record) {
                $record->stagiairesPivot()->attach($data['stagiaire_id'], ['date_affectation' => $data['date_affectation']]);
            }),
    ];
}

}
