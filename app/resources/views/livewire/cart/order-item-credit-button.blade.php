<div class="contents">
    @if ($usedCredit)
        <x-button class="full-width" variant="secondary" size="sm" color="danger" wire:click="removeCredit">
            <x-icon-close />
            Non usare il credito
        </x-button>
    @elseif ($eligible)
        <x-button class="full-width" variant="secondary" size="sm" color="primary" wire:click="useCredit">
            <x-icon-dollar />
            Usa il tuo credito
        </x-button>
    @endif
</div>
