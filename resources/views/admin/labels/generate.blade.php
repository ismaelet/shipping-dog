<x-layouts.admin-dashboard>
	<x-dashboard.typography.main-heading class="mb-12">Genera le etichette</x-dashboard.typography.main-heading>

	<x-dashboard.form.panel title="Etichette da generare">

		<form
			class="grid gap-12"
			method="POST"
		>
			@csrf
			@session('success')
				<x-form.group>
					<x-inputs.alert-success>{{ $value }}</x-inputs.alert-success>
				</x-form.group>
			@endsession

			<x-form.group>
				<x-inputs.label>Scarica dall'ordine, all'ordine:</x-inputs.label>
				<x-inputs.search-input
					search="orders"
					stored-name="first_order_id"
					:stored-value="$firstCart->id ?? ''"
					:shown-value="$firstCart ? $firstCart->id . ' - ' . $firstCart->address->full_name : ''"
					placeholder="Scarica dall'ordine numero"
					:required="true"
					data-size="2"
				/>

				<x-inputs.search-input
					search="orders"
					stored-name="last_order_id"
					:stored-value="$lastCart->id ?? ''"
					:shown-value="$lastCart ? $lastCart->id . ' - ' . $lastCart->address->full_name : ''"
					placeholder="Scarica dall'ordine numero"
					:required="true"
					data-size="2"
				/>

				@session('error')
					<x-inputs.alert-error>{{ $value }}</x-inputs.alert-error>
				@endsession
			</x-form.group>

			<div class="flex flex-wrap justify-center gap-4">
				<x-inputs.button
					type="submit"
					name="submit"
					value="generate"
				>
					{!! embed_icon('download') !!}
					Scarica le etichette
				</x-inputs.button>

				<x-inputs.button
					type="submit"
					name="submit"
					value="process_all"
					variant="secondary"
				>
					{!! embed_icon('inventory') !!}
					Elabora gli ordini
				</x-inputs.button>
			</div>
		</form>

	</x-dashboard.form.panel>
</x-layouts.admin-dashboard>
