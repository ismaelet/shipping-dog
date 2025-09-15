@pushOnce('styles')
	<style>
		.dashboard-filter fieldset>.fi-sc {
			grid-template-columns: 1fr auto;
			align-items: flex-end;
		}

		.fi-ac-btn-action {
			width: 100%;
		}
	</style>
@endpushOnce

<div class="dashboard-filter">
	<form>
		{{ $this->form }}
	</form>

	<x-filament-actions::modals />
</div>
