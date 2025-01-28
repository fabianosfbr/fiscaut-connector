<?php

declare(strict_types=1);

namespace App\Filament\Resources\TabelaResource\Pages;

use App\Filament\Resources\TabelaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTabelas extends ListRecords
{
    protected static string $resource = TabelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //  Actions\CreateAction::make(),
        ];
    }
}
