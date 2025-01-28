<x-filament-panels::page>
    <form wire:submit="create">

        {{ $this->form }}

        <div class="pt-4">
            <x-filament::button type="submit">
            Salvar
        </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
