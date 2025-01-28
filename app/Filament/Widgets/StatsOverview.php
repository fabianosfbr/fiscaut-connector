<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Empresas', '800'),
            Stat::make('Clientes', '21k'),
            Stat::make('Fornecedores', '28k'),
        ];
    }
}
