@php
	$no_cover = strpos($seller->cover(), '0.jpg');
@endphp

<x-layouts.seller-dashboard :$seller>
	<div class="columns-30rem grid gap-8 lg:gap-20">
		<div class="grid gap-[inherit]">
			<form
				class="form surface dashboard-panel grid grid-cols-1 content-center"
				method="POST"
			>
				<x-typography.component-heading>Nome e descrizione</x-typography.component-heading>

				<div class="grid gap-4">

					<div class="mt-8 grid grid-cols-1 gap-4">
						<div class="grid grid-cols-1 gap-2">
							<x-inputs.label>Nome:</x-inputs.label>
							<input
								type="text"
								class="input"
								name="name"
								placeholder="Nome del negozio"
								value="{{ $seller->name }}"
								maxlength="50"
								required
							>
						</div>

						<div class="grid grid-cols-1 gap-2">
							<x-inputs.label>Descrizione (opzionale):</x-inputs.label>
							<textarea
							 class="textarea"
							 name="desc"
							 placeholder="Descrizione del negozio"
							 rows="4"
							>{{ $seller->description }}</textarea>
						</div>

						<div class="grid grid-cols-1 gap-2">
							<div class="flex items-baseline justify-between">
								<x-inputs.label>Ordina prodotti:</x-inputs.label>

								<span class="inlb mt-2">
									{{ info_box('Puoi scegliere se ordinare i prodotti dal più poplare o dal più recente al meno, oppure seleziona "ordine personalizzato" per seguire l\'ordine della tua lista dei lavori. Questo campo influenza solo l\'ordine predefinito, i clienti potranno comunque riordinare i prodotti in ordine di popolarità o di data di creazione.') }}
								</span>
							</div>

							<select
								class="select"
								name="sort"
							>
								<option
									value="1"
									@if ($seller->sort == 1) selected @endif
								>Dal più venduto al meno</option>
								<option
									value="2"
									@if ($seller->sort == 2) selected @endif
								>Dal più recente al meno</option>
								<option
									value="3"
									@if ($seller->sort == 3) selected @endif
								>Ordine personalizzato</option>
							</select>
						</div>
					</div>

					<div class="form-foot">
						<x-button
							type="submit"
							name="edit"
							class="btn"
						>Modifica</x-button>
					</div>
				</div>
			</form>

			<form
				class="form surface dashboard-panel"
				id="socials"
				method="POST"
			>
				<x-typography.component-heading>Socials & Contatti</x-typography.component-heading>

				@if ($saved == 'socials')
					<p class="confirmation mar-t-1">Modifiche salvate</p>
				@endif

				<div class="gap1 mt-4 grid">
					<x-inputs.label>Website:</x-inputs.label>
					<input
						type="text"
						class="input"
						name="site"
						value="{{ $row_link['site'] ?? '' }}"
						placeholder="URL website"
					>
					<x-inputs.label>Facebook:</x-inputs.label>
					<input
						type="text"
						class="input"
						name="fb"
						value="{{ $row_link['facebook'] ?? '' }}"
						placeholder="Username Facebook o URL completo"
					>
					<x-inputs.label>Instagram:</x-inputs.label>
					<input
						type="text"
						class="input"
						name="inst"
						value="{{ $row_link['instagram'] ?? '' }}"
						placeholder="Username Instagram o URL completo"
					>
					<x-inputs.label>Twitter:</x-inputs.label>
					<input
						type="text"
						class="input"
						name="twit"
						value="{{ $row_link['twitter'] ?? '' }}"
						placeholder="Username Twitter o URL completo"
					>
					<x-inputs.label>YouTube:</x-inputs.label>
					<input
						type="text"
						class="input"
						name="yt"
						value="{{ $row_link['youtube'] ?? '' }}"
						placeholder="Username YouTube o URL completo"
					>
				</div>

				<div class="form-foot">
					<x-button
						type="submit"
						name="edit-socials"
						class="btn"
					>Modifica</x-button>
				</div>
			</form>
		</div>

		<div class="form surface dashboard-panel grid grid-cols-1">

			<div class="">
				<x-typography.component-heading>Immagini negozio</x-typography.component-heading>
			</div>

			<!-- Profile icon -->
			<form
				class="file-form grid grid-cols-1 pt-4 text-center"
				method="POST"
				enctype="multipart/form-data"
				action="{{ route('seller-dashboard.icon', $seller) }}"
			>

				<div class="grid grid-cols-1 justify-items-center gap-4">
					<p class="font-heading text-lg text-neutral-700">Immagine di profilo:</p>

					<img
						class="w-40 rounded"
						src="{{ $seller->icon() }}"
					/>

					<p class="">L'immagine di profilo deve avere il lato minore di almeno <strong>150px x
							150px</strong>. Questa immagine appare nella pagina del tuo negozio e nelle pagine dei tuoi articoli.</p>

					<x-button
						type="button"
						class="button--auto-width file-cancel"
						variant="secondary"
						onclick="$(this).parent().find('.file-inp').click();"
					>
						{!! embed_icon('upload') !!}
						Cambia la foto
					</x-button>

					<div class="file-load"></div>

					<input
						type="file"
						name="icon"
						class="file-inp hidden"
					>
					<button
						type="submit"
						name="edit-icon"
						class="file-submit hidden"
					></button>
				</div>
			</form>

			<!-- Cover image -->
			<form
				class="file-form border-surface-500 mt-12 grid grid-cols-1 justify-items-center gap-4 border-t border-dashed pt-8"
				method="POST"
				enctype="multipart/form-data"
				action="{{ route('seller-dashboard.cover', $seller) }}"
			>

				<p class="font-heading text-lg text-neutral-700">Immagine di copertina:</p>

				@if (!$no_cover)
					<img
						class="max-h-140 w-full rounded object-cover"
						src="{{ $seller->cover() }}"
					/>
				@endif

				<p class="mar-t-1 img-txt mar-h-auto">L'immagine di copertina deve avere una dimensione di almeno <strong>1600px x
						600px</strong>. Questa immagine è usata nella parte alta della pagina del tuo negozio.</p>

				<x-button
					type="button"
					class="button--auto-width file-cancel"
					variant="secondary"
					onclick="$(this).parent().find('.file-inp').click();"
				>
					{!! embed_icon('upload') !!}
					{{ $no_cover ? 'Carica copertina' : 'Cambia copertina' }}
				</x-button>

				<div class="file-load"></div>

				<input
					type="file"
					name="cover"
					class="file-inp hidden"
				>
				<button
					type="submit"
					name="edit-cover"
					class="file-submit hidden"
				></button>
			</form>
		</div>
	</div>
</x-layouts.seller-dashboard>
