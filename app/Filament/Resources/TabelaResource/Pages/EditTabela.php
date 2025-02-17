<?php

declare(strict_types=1);

namespace App\Filament\Resources\TabelaResource\Pages;

use App\Filament\Resources\TabelaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTabela extends EditRecord
{
    protected static string $resource = TabelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
