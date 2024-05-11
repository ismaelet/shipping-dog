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
	</style>
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

			<div class="stack-content grid">
				{{-- Products --}}
				@include('ecommerce.designs.edit.products')
			</div>

		</section>

		{{-- Delete button --}}
		@include('ecommerce.designs.edit.delete-button')
	</div>
</x-layouts.main>
