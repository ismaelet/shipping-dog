<x-layouts.admin-dashboard>
	<x-dashboard.typography.main-heading class="mb-12">Genera le etichette</x-dashboard.typography.main-heading>

	<div class="grid gap-8 lg:grid-cols-7">

		{{-- Add a specific parcel --}}
		<div class="lg:col-span-3">
			<x-dashboard.form.panel title="Trasmetti una spedizione">
				<form
					class="grid gap-12"
					method="POST"
					action="{{ route('gls.parcels.add') }}"
				>
					@csrf

					<x-form.group>
						<x-inputs.label>Spedizione da trasmettere:</x-inputs.label>
						<x-inputs.search-input
							search="orders"
							stored-name="order_id"
							stored-value="{{ $defaultOrder?->id }}"
							shown-value="{{ $defaultOrder ? $defaultOrder->id . ' - ' . $defaultOrder->address->full_name : '' }}"
							placeholder="Spedizione da trasmettere"
							:required="true"
						/>

						@session('add_single_parcel_success')
							<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
						@endsession

						@session('add_single_parcel_error')
							<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
						@endsession
					</x-form.group>

					<x-inputs.button
						type="submit"
						name="submit"
						value="generate"
					>
						{!! embed_icon('shipping') !!}
						Trasmetti la spedizione
					</x-inputs.button>
				</form>
			</x-dashboard.form.panel>
		</div>

		{{-- Generate one label --}}
		<div class="lg:col-span-4">
			<x-dashboard.form.panel title="Trasmetti tutte le spedizioni">
				<div class="grid gap-12">

					@session('add_parcels_success')
						<x-form.group>
							<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
						</x-form.group>
					@endsession

					<x-form.group>
						<x-inputs.label>{{ $orderCount }} spedizioni da trasmettere</x-inputs.label>

						@session('error')
							<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
						@endsession
					</x-form.group>

					<div class="flex flex-wrap justify-center gap-4">
						<form
							method="POST"
							action="{{ route('gls.parcels.add.all') }}"
						>
							@csrf
							<x-inputs.button type="submit">
								{!! embed_icon('shipping') !!}
								Trasmetti tutte le spedizioni
							</x-inputs.button>

							@session('add_all_parcels_success')
								<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
							@endsession

							@session('add_all_parcels_error')
								<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
							@endsession
						</form>

						<form
							method="POST"
							action="{{ route('admin.labels.download') }}"
						>
							@csrf
							<x-inputs.button
								type="submit"
								variant="secondary"
							>
								{!! embed_icon('download') !!}
								Scarica le etichette
							</x-inputs.button>
						</form>
					</div>
				</div>
			</x-dashboard.form.panel>
		</div>
	</div>
</x-layouts.admin-dashboard>
