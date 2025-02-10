<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;
use App\Models\Acumulador;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Session;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableEditAcumulador extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $codi_emp;

    public function mount()
    {
        $this->codi_emp = Session::get('codi_emp');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Acumulador::query()->where('codi_emp', $this->codi_emp)->orderBy('nome_acu', 'asc'))
            ->columns([
                TextColumn::make('nome_acu')
                    ->label('Nome'),
                TextColumn::make('codi_acu')
                    ->label('CNPJ'),
                IconColumn::make('fiscaut_sync')
                    ->label('Fiscaut')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('fiscaut_sync')
                    ->label('Fiscaut Sync'),
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
        return view('livewire.table-edit-acumulador');
    }
}
