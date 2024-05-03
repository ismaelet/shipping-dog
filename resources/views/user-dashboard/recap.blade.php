@php
	$user = Auth::user();

	$directory = public_path('uploads/free');
	$freeMaterials = glob($directory . '/*.pdf');
@endphp

<x-templates.user-dashboard>

	<x-templates.user-dashboard.panel
		:icon="'user'"
		:title="'Account'"
	>
		<div class="grid gap-6">
			<div>
				<h4 class="font-heading mb-2 text-xl">I miei dati</h4>

				@if ($user->fullName())
					<p>Nominativo: <span class="font-semibold">{{ $user->fullName() }}</span></p>
				@endif

				<p>Indirizzo e-mail: <span class="font-semibold">{{ $user->email }}</span></p>
			</div>

			<div>
				<x-inputs.button
					href="{{ route('user-dashboard.personal-data') }}"
					variant="secondary"
					size="small"
				>Modifica</x-inputs.button>
			</div>
		</div>
	</x-templates.user-dashboard.panel>

	<x-templates.user-dashboard.panel
		:icon="'documents'"
		:title="'Materiali gratuiti'"
	>
		<div class="grid gap-2">
			@foreach ($freeMaterials as $material)
				<p>
					<a
						href="{{ explode('/public/', $material)[1] }}"
						download
						class="flex w-fit items-center gap-2 font-medium italic"
					>

						<span class="text-primary w-3">
							{{ import_svg('icons/download') }}
						</span>

						{{ pathinfo($material, PATHINFO_FILENAME) }}
					</a>
				</p>
			@endforeach
		</div>
	</x-templates.user-dashboard.panel>

</x-templates.user-dashboard>
