@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', () => {
			initLightboxes();
			initFileForms();
		});

		function initLightboxes() {
			document.querySelectorAll('[data-lightbox-open]').forEach((button) => {
				button.addEventListener('click', () => {
					const name = button.dataset.lightboxOpen;
					const lightbox = document.querySelector(`[data-lightbox="${name}"]`);

					if (!lightbox) {
						return;
					}

					lightbox.classList.remove('hidden');
					lightbox.classList.add('flex');
				});
			});

			document.querySelectorAll('[data-lightbox-close]').forEach((button) => {
				button.addEventListener('click', () => {
					closeLightbox(button.closest('[data-lightbox]'));
				});
			});

			document.addEventListener('keydown', (event) => {
				if (event.key !== 'Escape') {
					return;
				}

				document.querySelectorAll('[data-lightbox]').forEach(closeLightbox);
			});
		}

		function closeLightbox(lightbox) {
			if (!lightbox) {
				return;
			}

			lightbox.classList.add('hidden');
			lightbox.classList.remove('flex');
		}

		function initFileForms() {
			document.querySelectorAll('[data-file-form]').forEach((form) => {
				const input = form.querySelector('[data-file-input]');
				const loader = form.querySelector('[data-file-load]');
				const submit = form.querySelector('[data-file-submit]');

				if (!input || !submit) {
					return;
				}

				input.addEventListener('change', () => {
					if (!input.files.length) {
						return;
					}

					if (loader) {
						loader.textContent = input.files[0].name;
					}

					submit.click();
				});
			});
		}
	</script>
@endpushOnce

<x-layouts.main
	:title="$title"
	:description="$description"
	:thumbnail="$thumbnail"
	:location-tag="$location"
>
	<div class="">
		@if ($collection->image(1024))
			<div
				class="stack-content sm:h-120 grid h-56 justify-items-center overflow-hidden rounded-b-md bg-neutral-500 dark:bg-transparent"
			>
				<img
					src="{{ $collection->image(1024) }}"
					alt=""
					class="h-[inherit] w-full object-cover blur-sm"
				/>

				<img
					src="{{ $collection->image(1024) }}"
					alt=""
					class="relative h-[inherit]"
				/>
			</div>
		@else
			<div class="bg-primary-400 h-28 rounded-b-md sm:h-60"></div>
		@endif

		<div class="w-full">
			<div class="relative z-10 mx-auto -mt-12 w-20">
				<a
					class="border-(--color-background-400) bg-(--surface-secondary) block aspect-square rounded-[10px] border-[0.4rem] bg-cover bg-center"
					href="{{ $seller->url() }}"
					@style([
					    'background-image: url(' . asset(ltrim($seller->icon(), '/')) . ')' => $seller->icon(),
					])
				></a>
			</div>

			<div class="mt-4 grid grid-cols-1 gap-4 text-center">
				<div class="">
					<p class="font-heading uppercase tracking-widest">
						<a href="{{ $seller->url() }}">
							{{ $seller->name }}
						</a>
					</p>

					@if ($userIsOwner)
						<livewire:ecommerce.seller.collection.name-input
							:seller="$seller"
							:collection="$collection"
						/>
					@else
						<h1 class="text-750 leading-none">
							{{ $collection->name }}
						</h1>
					@endif
				</div>

				@if ($seller->description)
					<p class="max-w-176 mx-auto">
						{{ $seller->description }}
					</p>
				@endif
			</div>
		</div>
	</div>

	<div>
		<div class="w-full">
			<div class="py-12">
				<div class="border-(--surface-secondary) flex flex-wrap items-center gap-3 border-b pb-3 max-[600px]:gap-2">
					<h1 class="text-xl tracking-[0.02em] max-[600px]:text-[1.1rem]">
						<a
							class="txt-c1-h"
							href="{{ $seller->url() }}"
						>
							{{ $seller->name }}
						</a>
					</h1>

					<h1 class="text-xl tracking-[0.02em] max-[600px]:text-[1.1rem]">
						&gt;
					</h1>

					<livewire:ecommerce.seller.collection.name-display :collection="$collection" />
				</div>

				@if ($userIsOwner)
					<div
						class="bg-background-400 mb-8 mt-4 flex flex-wrap gap-3 [--icon-size:1em] max-[600px]:flex-col max-[600px]:items-start"
					>
						<button
							type="button"
							class="inline-flex cursor-pointer items-center gap-[0.35rem] border-b-2 border-[#067e00] bg-transparent pb-[0.15em] pr-[0.2em] text-sm leading-[1.8] text-[#067e00] [&_svg]:fill-[#067e00]"
							data-lightbox-open="add-designs"
						>
							<x-icon-add class="h-4 w-4" />
							<span>Aggiungi una grafica</span>
						</button>

						@if ($designsToRemove->isNotEmpty())
							<button
								type="button"
								class="inline-flex cursor-pointer items-center gap-[0.35rem] border-b-2 border-[#ffc845] bg-transparent pb-[0.15em] pr-[0.2em] text-sm leading-[1.8] text-[#ffc845] [&_svg]:fill-[#ffc845]"
								data-lightbox-open="remove-designs"
							>
								<x-icon-delete class="h-4 w-4" />
								<span>Rimuovi una grafica</span>
							</button>
						@endif

						<button
							type="button"
							class="inline-flex cursor-pointer items-center gap-[0.35rem] border-b-2 border-[#0874a8] bg-transparent pb-[0.15em] pr-[0.2em] text-sm leading-[1.8] text-[#0874a8] [&_svg]:fill-[#0874a8]"
							data-lightbox-open="change-cover"
						>
							<x-icon-gallery class="h-4 w-4" />
							<span>Cambia copertina</span>
						</button>

						<form
							method="POST"
							action="{{ route('seller.collection.delete', [$seller, $collection]) }}"
							onsubmit="return confirm('Sei sicuro di voler eliminare la collezione?')"
						>
							@csrf
							@method('DELETE')

							<button
								type="submit"
								class="inline-flex cursor-pointer items-center gap-[0.35rem] border-b-2 border-[#ff0000] bg-transparent pb-[0.15em] pr-[0.2em] text-sm leading-[1.8] text-[#ff0000] [&_svg]:fill-[#ff0000]"
							>
								<x-icon-delete class="h-4 w-4" />
								<span>Elimina collezione</span>
							</button>
						</form>
					</div>
				@endif

				<div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
					@foreach ($products as $product)
						<x-product-card :product="$product" />
					@endforeach
				</div>
			</div>
		</div>
	</div>

	@if ($userIsOwner)
		<div
			class="z-1000 fixed inset-0 hidden items-center justify-center p-4"
			data-lightbox="add-designs"
		>
			<div
				class="absolute inset-0 bg-black/65"
				data-lightbox-close
			></div>

			<div class="max-w-240 relative z-10 max-h-[min(90vh,60rem)] w-full overflow-auto">
				<form
					class="bg-background-400 rounded-xl p-6"
					method="POST"
					action="{{ route('seller.collection.designs.add', [$seller, $collection]) }}"
				>
					@csrf

					<h1 class="mb-4 text-2xl">
						Aggiungi una grafica
					</h1>

					<div class="mb-6">
						<p class="">
							Seleziona i lavori da aggiungere alla collezione:
						</p>

						<div class="my-8 grid grid-cols-[repeat(auto-fill,minmax(9rem,1fr))] gap-4 text-center">
							@foreach ($designsToAdd as $design)
								<label class="block">
									<input
										type="checkbox"
										class="peer hidden"
										name="designs[]"
										value="{{ $design->id }}"
									>

									<div
										class="cursor-pointer overflow-hidden rounded-[5px] border-4 border-[#ebebeb] transition-colors peer-checked:border-[#2ab800]"
									>
										<div
											class="aspect-square bg-cover bg-center"
											@style([
											    'background-image: url(' . asset(ltrim($design->image(), '/')) . ')' => $design->image(),
											])
										></div>
									</div>
								</label>
							@endforeach
						</div>
					</div>

					<div class="flex flex-wrap justify-center gap-2">
						<x-button type="submit">
							<x-icon-add class="h-4 w-4" />
							Aggiungi
						</x-button>

						<x-button
							type="button"
							variant="secondary"
							data-lightbox-close
						>
							<x-icon-close class="h-4 w-4" />
							Annulla
						</x-button>
					</div>
				</form>
			</div>
		</div>

		<div
			class="z-1000 fixed inset-0 hidden items-center justify-center p-4"
			data-lightbox="remove-designs"
		>
			<div
				class="absolute inset-0 bg-black/65"
				data-lightbox-close
			></div>

			<div class="max-w-240 relative z-10 max-h-[min(90vh,60rem)] w-full overflow-auto">
				<form
					class="bg-background-400 rounded-xl p-6"
					method="POST"
					action="{{ route('seller.collection.designs.remove', [$seller, $collection]) }}"
				>
					@csrf
					@method('DELETE')

					<h1 class="mb-4 text-2xl">
						Rimuovi dalla collezione
					</h1>

					<div class="mb-6">
						<p class="mb-4">
							Seleziona le grafiche che vuoi rimuovere dalla collezione (saranno rimosse solo dalla collezione, non eliminate
							dal tuo account):
						</p>

						<div class="grid grid-cols-[repeat(auto-fill,minmax(9rem,1fr))] gap-4">
							@foreach ($designsToRemove as $design)
								<label class="block">
									<input
										type="checkbox"
										class="peer hidden"
										name="designs[]"
										value="{{ $design->id }}"
									>

									<div
										class="cursor-pointer overflow-hidden rounded-[5px] border-4 border-[#ebebeb] transition-colors peer-checked:border-[#c71619]"
									>
										<div
											class="aspect-square bg-contain bg-center bg-no-repeat"
											@style([
											    'background-image: url(' . asset(ltrim($design->image(), '/')) . ')' => $design->image(),
											])
										></div>
									</div>
								</label>
							@endforeach
						</div>
					</div>

					<div class="flex flex-wrap justify-center gap-2">
						<x-button
							type="submit"
							color="danger"
						>
							<x-icon-delete class="h-4 w-4" />
							Rimuovi
						</x-button>

						<x-button
							type="button"
							variant="secondary"
							data-lightbox-close
						>
							<x-icon-close class="h-4 w-4" />
							Annulla
						</x-button>
					</div>
				</form>
			</div>
		</div>

		<div
			class="z-1000 fixed inset-0 hidden items-center justify-center p-4"
			data-lightbox="change-cover"
		>
			<div
				class="absolute inset-0 bg-black/65"
				data-lightbox-close
			></div>

			<div class="max-w-136 relative z-10 max-h-[min(90vh,60rem)] w-full overflow-auto">
				<form
					class="bg-background-400 rounded-xl p-6"
					method="POST"
					action="{{ route('seller.collection.cover.update', [$seller, $collection]) }}"
					enctype="multipart/form-data"
					data-file-form
				>
					@csrf

					<h1 class="mb-4 text-2xl">
						Cambia immagine di copertina
					</h1>

					<div>
						<p class="mb-4">
							Seleziona l'immagine di copertina per questa collezione:
						</p>

						<input
							type="file"
							name="image"
							class="input w-full"
							accept="image/*"
							data-file-input
						>

						<div
							class="mt-3 text-sm opacity-75"
							data-file-load
						></div>

						<button
							type="submit"
							class="hidden"
							data-file-submit
						></button>

						<div class="flex justify-center">
							<x-button
								type="button"
								variant="secondary"
								data-lightbox-close
							>
								<x-icon-close class="h-4 w-4" />
								Annulla
							</x-button>
						</div>
					</div>
				</form>
			</div>
		</div>
	@endif
</x-layouts.main>
