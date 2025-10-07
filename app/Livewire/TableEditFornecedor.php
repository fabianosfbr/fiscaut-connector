<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Fornecedor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TableEditFornecedor extends Component implements HasForms, HasTable
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
            ->query(Fornecedor::query()->where('codi_emp', $this->codi_emp)->orderBy('nome_for', 'asc'))
            ->columns([
                TextColumn::make('nome_for')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('cgce_for')
                    ->label('CNPJ'),
                TextColumn::make('codi_cta')
                    ->label('Conta Contábil')
                    ->badge(),
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
        return view('livewire.table-edit-fornecedor');
    }
}
