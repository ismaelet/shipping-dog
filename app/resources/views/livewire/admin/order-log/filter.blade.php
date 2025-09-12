@pushOnce('styles')
	<style>
		.dashboard-filter fieldset>.fi-sc {
			grid-template-columns: 1fr auto;
			align-items: flex-end;
		}

		.fi-ac-btn-action {
			width: 100%;
		}

		.fi-grid-col:has(.fi-checkbox-input) {
			grid-column: span 2;
		}
	</style>
@endpushOnce

<div class="dashboard-filter">
	<form wire:submit="create">
		{{ $this->form }}
	</form>

	<x-filament-actions::modals />
</div>
