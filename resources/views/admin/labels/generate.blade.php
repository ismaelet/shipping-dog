@pushOnce('styles')
	<style>
		.table-cell {
			cursor: pointer;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', () => {
			const submitButton = document.querySelector('[data-submit-parcels]')
			document.querySelectorAll('.table-cell').forEach(element => {
				element.addEventListener('click', () => {
					const row = element.closest('tr');
					const checkbox = row.querySelector('input[type="checkbox"]');

					checkbox.checked = !checkbox.checked;
				});
			});

			submitButton.addEventListener('click', () => {
				const form = submitButton.closest('form');
				const checkboxes = document.querySelectorAll('.table-cell input[type="checkbox"]:checked');

				if (checkboxes.length === 0) {
					alert('Seleziona almeno una spedizione da trasmettere');
					return;
				}

				checkboxes.forEach(checkbox => {
					if (checkbox.checked) {
						const input = document.createElement('input');

						input.type = 'hidden';
						input.name = 'orders[]';
						input.value = checkbox.value;

						form.appendChild(input);
					}
				});

				form.submit();

			});
		});
	</script>
@endpushOnce

<x-layouts.admin-dashboard>
	<x-dashboard.typography.main-heading class="mb-12">Genera le etichette</x-dashboard.typography.main-heading>

	<div class="grid gap-8">

		{{-- Generate one label --}}
		<x-dashboard.form.panel title="Trasmetti tutte le spedizioni">
			<div class="grid gap-12">

				@session('add_parcels_success')
					<x-form.group>
						<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
					</x-form.group>
				@endsession

				<x-form.group>
					<x-inputs.label>{{ $orderCount }} spedizioni da trasmettere</x-inputs.label>

					@session('parcels_success')
						<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
					@endsession

					@session('parcels_error')
						<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
					@endsession

					<x-table
						:columns="$columns"
						:records="$orders"
						:record-count="$orderCount"
						:search-bar="false"
					/>

					@session('error')
						<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
					@endsession
				</x-form.group>

				<div class="flex flex-wrap justify-center gap-4">
					<form
						method="POST"
						action="{{ route('gls.transmit') }}"
					>
						@csrf
						<x-inputs.button
							type="button"
							data-submit-parcels
						>
							{!! embed_icon('shipping') !!}
							Trasmetti tutte le spedizioni
						</x-inputs.button>
					</form>
				</div>
			</div>
		</x-dashboard.form.panel>
	</div>
</x-layouts.admin-dashboard>
