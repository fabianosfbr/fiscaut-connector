<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\PlanoDeConta;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableEditPlanoDeConta extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(PlanoDeConta::query())
            ->defaultSort('codi_cta', 'asc')
            ->recordClasses(function (Model $record) {
                if ($record->tipo_cta == 'A') {
                    return 'bg-gray-100 dark:bg-gray-800';
                }
            })
            ->columns([
                TextColumn::make('codi_cta')
                    ->label('Codigo')
                    ->searchable(),
                TextColumn::make('nome_cta')
                    ->label('Nome')
                    ->alignEnd()
                    ->searchable(),
                TextColumn::make('clas_cta')
                    ->label('Classificação')
                    ->alignEnd()
                    ->searchable(),
                    TextColumn::make('tipo_cta')
                    ->label('Tipo')
                    ->badge(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.table-edit-plano-de-conta');
    }
}
