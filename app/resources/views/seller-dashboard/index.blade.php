<x-layouts.seller-dashboard>
	<div class="grid grid-cols-1 gap-8">
		<livewire:seller.activation-request :$seller />

		<div class="columns-30rem grid items-start gap-8 lg:gap-20">
			<div class="grid gap-[inherit]">
				<form
					class="form surface dashboard-panel grid grid-cols-1 content-center"
					method="POST"
					action="{{ route('seller-dashboard.settings', $seller) }}"
				>
					@csrf

					<x-heading.component>Nome e descrizione</x-heading.component>

					<div class="grid grid-cols-1 gap-4">
						<div class="mt-8 grid grid-cols-1 gap-4">
							<div class="grid grid-cols-1 gap-2">
								<x-label>Nome:</x-label>
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
								<x-label>Descrizione (opzionale):</x-label>
								<textarea
								 class="textarea"
								 name="description"
								 placeholder="Descrizione del negozio"
								 rows="4"
								 maxlength="1000"
								>{{ $seller->description }}</textarea>
							</div>

							<div class="grid grid-cols-1 gap-2">
								<div class="flex items-baseline justify-between">
									<x-label>Ordina prodotti:</x-label>

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

						@if (session('success_settings'))
							<x-alerts.success class="mt-4">{!! session('success_settings') !!}</x-alerts.success>
						@endif

						<div class="form-foot">

							<x-button
								type="submit"
								name="edit"
								class="btn"
							>Modifica</x-button>
						</div>
					</div>
				</form>

				{{-- Contacts --}}
				<form
					class="form surface dashboard-panel"
					id="contacts"
					method="POST"
					action="{{ route('seller-dashboard.contacts', $seller) }}"
				>
					@method('PUT')
					@csrf

					<x-heading.component>Socials & Contatti</x-heading.component>

					@if (session('success_socials'))
						<x-alerts.success class="mt-4">{!! session('success_socials') !!}</x-alerts.success>
					@endif

					<div class="form-fields mt-4">
						<div class="form-field">
							<x-label>Website:</x-label>
							<input
								type="text"
								class="input"
								name="website"
								value="{{ $user->website() }}"
								placeholder="URL website"
							>
							@error('website')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>Facebook:</x-label>
							<input
								type="text"
								class="input"
								name="facebook"
								value="{{ $user->facebook() }}"
								placeholder="Username Facebook o URL completo"
							>
							@error('facebook')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>Instagram:</x-label>
							<input
								type="text"
								class="input"
								name="instagram"
								value="{{ $user->instagram() }}"
								placeholder="Username Instagram o URL completo"
							>
							@error('instagram')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>Twitter:</x-label>
							<input
								type="text"
								class="input"
								name="twitter"
								value="{{ $user->twitter() }}"
								placeholder="Username Twitter o URL completo"
							>
							@error('twitter')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>YouTube:</x-label>
							<input
								type="text"
								class="input"
								name="youtube"
								value="{{ $user->youtube() }}"
								placeholder="Username YouTube o URL completo"
							>
							@error('youtube')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>TikTok:</x-label>
							<input
								type="text"
								class="input"
								name="tiktok"
								value="{{ $user->tiktok() }}"
								placeholder="Username TikTok o URL completo"
							>
							@error('tiktok')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>

						<div class="form-field">
							<x-label>BlueSky:</x-label>
							<input
								type="text"
								class="input"
								name="bluesky"
								value="{{ $user->bluesky() }}"
								placeholder="Username BlueSky o URL completo"
							>
							@error('bluesky')
								<x-alerts.error class="">
									{{ $message }}
								</x-alerts.error>
							@enderror
						</div>
					</div>

					<div class="form-foot">
						<x-button type="submit">Modifica</x-button>
					</div>
				</form>
			</div>

			<div class="form surface dashboard-panel grid grid-cols-1">
				{{-- Images --}}
				<div class="">
					<x-heading.component>Immagini negozio</x-heading.component>
					<form
						class="file-form grid grid-cols-1 pt-4 text-center"
						method="POST"
						enctype="multipart/form-data"
						action="{{ route('seller-dashboard.icon', $seller) }}"
					>

						@csrf

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

							<div class="file-load">{!! embed_icon('loading') !!}</div>

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
						@csrf

						<p class="font-heading text-lg text-neutral-700">Immagine di copertina:</p>

						@if (!$noCover)
							<img
								class="max-h-140 w-full rounded object-cover"
								src="{{ $seller->cover() }}"
							/>
						@endif

						<p class="mar-t-1 img-txt mar-h-auto">L'immagine di copertina deve avere una dimensione di almeno <strong>1600px
								x
								600px</strong>. Questa immagine è usata nella parte alta della pagina del tuo negozio.</p>

						<x-button
							type="button"
							class="button--auto-width file-cancel"
							variant="secondary"
							onclick="$(this).parent().find('.file-inp').click();"
						>
							{!! embed_icon('upload') !!}
							{{ $noCover ? 'Carica copertina' : 'Cambia copertina' }}
						</x-button>

						<div class="file-load">{!! embed_icon('loading') !!}</div>

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
		</div>
	</div>
</x-layouts.seller-dashboard>
