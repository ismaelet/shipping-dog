<div class="contents">
	@if ($usedCredit)
		<x-button
			class="full-width"
			variant="secondary"
			size="small"
			color="danger"
			wire:click="removeCredit"
		>
			<x-icon-close />
			Non usare il credito
		</x-button>
	@elseif ($elegible)
		<x-button
			class="full-width"
			variant="secondary"
			size="small"
			color="primary"
			wire:click="useCredit"
		>
			<x-icon-dollar />
			Usa il tuo credito
		</x-button>
	@endif
</div>
