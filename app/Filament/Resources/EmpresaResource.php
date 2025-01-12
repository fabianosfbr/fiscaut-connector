<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Empresa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchDebounce('750ms')
            ->searchPlaceholder('Pesquisar (Cod. Domínio, Nome)')
            ->columns([
                TextColumn::make('codi_emp')
                    ->label('Cod. Domínio')
                    ->searchable(),
                TextColumn::make('razao_emp')
                    ->label('Nome da empresa')
                    ->searchable(),
                TextColumn::make('cgce_emp')
                    ->label('CNPJ'),
                TextColumn::make('iest_emp')
                    ->label('IE'),
                TextColumn::make('imun_emp')
                    ->label('IM'),


            ])
            ->filters([
                //
            ])
            ->actions([
                //  Tables\Actions\EditAction::make(),
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
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
