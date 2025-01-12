<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Tabela;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TabelaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TabelaResource\RelationManagers;

class TabelaResource extends Resource
{
    protected static ?string $model = Tabela::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações da empresa')
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
                            ->label('Habilitar sincronização com Fiscaut')


                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('table_name')
                    ->label('Nome da tabela')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('table_description')
                    ->label('Descrição da tabela')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('count_rows')
                    ->label('Nº de registros'),
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
            'index' => Pages\ListTabelas::route('/'),
           // 'create' => Pages\CreateTabela::route('/create'),
            'edit' => Pages\EditTabela::route('/{record}/edit'),
        ];
    }
}
