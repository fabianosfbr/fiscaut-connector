<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TabelaResource\Pages;
use App\Models\Tabela;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TabelaResource extends Resource
{
    protected static ?string $model = Tabela::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detalhes da tabela')
                    ->schema([
                        Forms\Components\TextInput::make('table_name')
                            ->label('Nome da tabela')
                            ->disabled(),

                        Forms\Components\TextInput::make('count_rows')
                            ->label('Nº de registros')
                            ->disabled(),

                        Forms\Components\TextInput::make('table_description')
                            ->label(label: 'Descrição da tabela'),

                        Forms\Components\Toggle::make('sync')
                            ->label('Habilitar sincronização com Fiscaut'),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('table_description', 'desc')
            ->columns([
                TextColumn::make('table_name')
                    ->label('Nome da tabela')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('table_description')
                    ->label('Descrição')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('count_rows')
                    ->label('Nº de registros'),
                IconColumn::make('sync')
                    ->label('Sincroniza com Fiscaut')
                    ->boolean(),
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
            'index' => Pages\ListTabelas::route('/'),
            // 'create' => Pages\CreateTabela::route('/create'),
            'edit' => Pages\EditTabela::route('/{record}/edit'),
        ];
    }
}
