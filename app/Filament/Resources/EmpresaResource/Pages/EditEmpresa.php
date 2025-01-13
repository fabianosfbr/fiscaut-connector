<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use Filament\Actions;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EmpresaResource;

class EditEmpresa extends EditRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Voltar')
                ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
                ->color('gray'),
            Action::make('save')
                ->label('Salvar')
                ->action('save'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
