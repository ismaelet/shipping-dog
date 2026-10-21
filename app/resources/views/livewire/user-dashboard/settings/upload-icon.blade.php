<x-user-dashboard.settings-section
	title="Immagine profilo"
	text="Personalizza il tuo account con una nuova immagine."
	icon="image"
	tone="detail-2"
>
	@session('success')
		<x-alerts.success class="mb-5">{{ session('success') }}</x-alerts.success>
	@endsession

	<div class="bg-background-400/60 flex flex-wrap items-center gap-5 rounded-2xl p-5">
		<div class="size-20 shrink-0 overflow-hidden rounded-full">
			<x-inputs.replace-image :image="$user->icon()" />
		</div>

		<div class="min-w-0 flex-1">
			<p class="text-neutral-heading text-sm font-bold">Carica una nuova foto</p>
			<p class="text-neutral-text text-xs">JPG, PNG o WEBP. Dimensione massima consigliata: 2 MB.</p>
		</div>

		<x-button
			class="relative"
			variant="secondary"
			size="small"
		>
			Scegli immagine
			<input
				type="file"
				accept="image/*"
				class="absolute inset-0 cursor-pointer opacity-0"
				wire:model="image"
			>
		</x-button>
	</div>

	@error('image')
		<x-alerts.error
			class="mt-4"
			:$message
		/>
	@enderror
</x-user-dashboard.settings-section>
