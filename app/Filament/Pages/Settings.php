<?php

namespace App\Filament\Pages;

use App\Models\Configuracao;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Configurações';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Configuração';

    protected static ?string $title = '';

    public ?string $api_key = null;

    public ?string $id = null;

    public ?string $fiscaut_token = null;

    public ?string $fiscaut_url = null;

    public function mount(): void
    {
        $config = Configuracao::first();
        $this->form->fill($config?->toArray() ?? []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configurações')
                    ->schema([
                        TextInput::make('api_key')
                            ->label('Chave de integração')
                            ->required(),
                        TextInput::make('external_url')
                            ->label('URL de acesso externo'),
                        Hidden::make('id'),
                    ]),

                Section::make('Acesso ao Fiscaut')
                    ->schema([
                        TextInput::make('fiscaut_token')
                            ->label('Token de acesso ao Fiscaut')
                            ->required(),
                        TextInput::make('fiscaut_url')
                            ->label('URL de acesso ao Fiscaut')
                            ->required(),
                    ]),

            ]);
    }

    public function create(): void
    {
        $dados = $this->form->getState();

        try {
            Configuracao::updateOrCreate(
                [
                    'id' => $dados['id'],
                ],
                $dados
            );

            // Notifica o usuário do sucesso
            Notification::make()
                ->success()
                ->title('Salvo com sucesso!')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Erro ao salvar')
                ->body('Ocorreu um erro ao salvar os dados.')
                ->send();
        }
    }
}
