<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Livewire\TableEditCliente;
use App\Livewire\TableEditEmpresa;
use Filament\Forms\Components\Tabs;
use App\Livewire\TableEditFornecedor;
use App\Livewire\TableEditPlanoDeConta;
use Filament\Forms\Components\Livewire;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmpresaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmpresaResource\RelationManagers;


class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';


    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('razao_emp')
                            ->label('Razão Social')
                            ->disabled(),
                        Forms\Components\TextInput::make('cgce_emp')
                            ->label('CNPJ/CPF')
                            ->disabled(),
                        Forms\Components\Toggle::make('sync')
                            ->label('Habilitar sincronização com Fiscaut')
                    ]),

                Forms\Components\Section::make('Serviços Sincronizados')
                    ->schema([



                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('Plano de conta')
                                    ->schema([
                                        Livewire::make(TableEditPlanoDeConta::class)
                                    ]),
                                Tabs\Tab::make('Fornecedor')
                                    ->schema([
                                        Livewire::make(TableEditFornecedor::class)
                                    ]),
                                Tabs\Tab::make('Cliente')
                                    ->schema([
                                        Livewire::make(TableEditCliente::class)
                                    ]),
                            ]),


                    ])


                // Forms\Components\Section::make('Serviços Sincronizados')
                //     ->schema([
                //         Forms\Components\Checkbox::make('cliente')
                //             ->label('Clientes'),
                //         Forms\Components\Checkbox::make(name: 'fornecedor')
                //             ->label('Fornecedores'),
                //         Forms\Components\Checkbox::make('plano_de_conta')
                //             ->label('Plano de Contas'),
                //     ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchDebounce('750ms')
            ->searchPlaceholder('Buscar (ID, Nome)')
            ->recordUrl(null)
            ->defaultSort('sync', 'desc')
            ->columns([
                TextColumn::make('codi_emp')
                    ->label('Cod. Domínio')
                    ->searchable(),
                TextColumn::make('razao_emp')
                    ->label('Nome da empresa')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getListLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('cgce_emp')
                    ->label('CNPJ'),
                TextColumn::make('iest_emp')
                    ->label('IE'),
                TextColumn::make('imun_emp')
                    ->label('IM'),
                IconColumn::make('sync')
                    ->label('Sincroniza com Fiscaut')
                    ->boolean()


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresas::route('/'),
            //  'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
