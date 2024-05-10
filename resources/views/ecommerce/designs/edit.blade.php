@pushOnce('styles')
	<style>
		.change-name {
			background-color: transparent;
			border-top: none;
			border-left: none;
			border-right: none;
			outline: none;
			line-height: unset;
		}

		.loading-products.fade {
			opacity: 0;
			pointer-events: none;
			transition: opacity 0.4s ease-in-out;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script>
		window.addEventListener('load', function() {
			const loading = document.querySelector('[data-loading-products]');

			loading.classList.add('fade');
		});
	</script>
@endpushOnce

<x-layouts.main :$title>

	<div
		class="mb-20 mt-20 grid grid-cols-1 gap-16"
		data-design-id="<?= $design->id ?>"
	>

		{{-- Name --}}
		@include('ecommerce.designs.edit.name')

		{{-- Design data --}}
		<div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-7 lg:gap-16">

			{{-- Left group --}}
			<section class="grid gap-8 lg:col-span-3">

				{{-- Light version --}}
				@include('ecommerce.designs.edit.image-light')

				@if (!$design->custom)
					{{-- Dark version --}}
					@include('ecommerce.designs.edit.image-dark')

					{{-- Vector --}}
					@include('ecommerce.designs.edit.image-vector')
				@endif

			</section>

			{{-- Right group --}}
			<div class="grid grid-cols-1 gap-8 lg:col-span-4">

				{{-- Tags --}}
				@include('ecommerce.designs.edit.tags')

				{{-- Colors --}}
				@if (!$design->custom)
					@include('ecommerce.designs.edit.background-color')

					@include('ecommerce.designs.edit.default-color')
				@endif

			</div>
		</div>

		{{-- Product list --}}
		<section class="grid grid-cols-1 gap-8">
			<div class="grid gap-2 text-center">
				<x-typography.section-heading>Prodotti collegati</x-typography.section-heading>

				<x-typography.section-subheading>Qui puoi modificare il colore dei capi e la dimensione di stampa nei prodotti con
					questa grafica.</x-typography.section-subheading>
			</div>

			<x-inputs.alert-error>Le funzionalità di modifica sono al momento intattive, ma verranno ripristinate al più presto.
				Ci scusiamo per l'inconveniente.</x-inputs.alert-error>

			<div class="stack-content grid">

				{{-- Products --}}
				@include('ecommerce.designs.edit.products')

				{{-- Loading --}}
				<div
					class="loading-products bg-background grid h-full w-full items-start justify-items-center py-8"
					data-loading-products
				>
					<div class="w-16">
						{!! embed_icon('loading') !!}
					</div>
				</div>
			</div>

		</section>

		{{-- Delete button --}}
		@include('ecommerce.designs.edit.delete-button')
	</div>
</x-layouts.main>
