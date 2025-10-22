<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\StagiairesTableWidget;
use App\Filament\Admin\Widgets\CoachsTableWidget;

class Dashboard extends BaseDashboard
{
    // c’est ici qu’il faut placer ta méthode 👇
    public function getWidgets(): array
    {
        return [
            StagiairesTableWidget::class,
            CoachsTableWidget::class,
        ];
    }
}




