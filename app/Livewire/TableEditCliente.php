<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableEditCliente extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Cliente::query())
            ->columns([
                TextColumn::make('nome_cli')
                ->label('Nome')
                ->searchable(),
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
        return view('livewire.table-edit-cliente');
    }
}
