<?php

declare(strict_types=1);

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Js;

class EditEmpresa extends EditRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        Session::put('codi_emp', $data['codi_emp']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Voltar')
                ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = '.Js::from($this->previousUrl ?? static::getResource()::getUrl()).')')
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
