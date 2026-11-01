@props(['status', 'label' => 'Salva'])

<div {{ $attributes->class('grid gap-3') }}>
    @if ($status === 'saved')
        <div wire:dirty.remove wire:loading.remove role="status">
            <x-alert type="success">Impostazioni salvate</x-alert>
        </div>
    @endif

    @if ($status === 'error')
        <div wire:loading.remove wire:target="save" role="alert">
            <x-alert type="error">Errore di salvataggio</x-alert>
        </div>
    @endif

    <span wire:loading wire:target="save" role="status" class="text-center">Salvataggio…</span>

    <x-button type="submit" size="sm" wire:loading.attr="disabled" wire:target="save">
        <x-icon-save />
        {{ $label }}
    </x-button>
</div>
