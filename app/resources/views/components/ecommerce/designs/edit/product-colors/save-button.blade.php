@props(['saveStatus'])

<div class="grid gap-4">
	<x-alerts.success
		wire:loading.remove
		wire:target="save"
		@class([
			'items-center justify-center gap-2',
			'flex' => $saveStatus === 'saved',
			'hidden' => $saveStatus !== 'saved',
		])
	>
		<x-icon-correct class="h-6 w-6" />
		Impostazioni salvate
	</x-alerts.success>

	<x-alerts.error
		wire:loading.remove
		wire:target="save"
		@class([
			'items-center justify-center gap-2',
			'flex' => $saveStatus === 'error',
			'hidden' => $saveStatus !== 'error',
		])
	>
		<x-icon-close class="h-6 w-6" />
		Errore di salvataggio
	</x-alerts.error>

	<x-button
		type="button"
		wire:click="save"
		wire:loading.attr="disabled"
		wire:target="save"
		@class([
			'flex' => in_array($saveStatus, ['active', 'error'], true),
			'hidden' => !in_array($saveStatus, ['active', 'error'], true),
		])
	>
		<x-icon-save class="h-6 w-6" />
		Salva
	</x-button>

	<span
		class="hidden w-12 justify-self-center"
		wire:loading.block
		wire:target="save"
	>
		<x-icon-loading class="h-6 w-6" />
	</span>
</div>
