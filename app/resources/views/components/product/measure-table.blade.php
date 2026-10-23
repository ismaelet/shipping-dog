@php

	$measures = $product->type->measures->sortBy('id');
	$sizes = $product->type->sizes;
	$material = $measures->where('size', 'mat')->first();

@endphp

@pushOnce('styles')
	<style>
		.measure-table { max-height: 100vh; overflow-y: auto; }
		.size-table-grid { display: grid; grid-template-columns: 0.5fr repeat(2, minmax(0, 1fr)); text-align: center; }
		.size-table-grid > * { padding: 0.5rem 0.3rem; border: 1px solid #ebebeb; border-width: 0 0 1px; font-size: 0.8rem; }
		.size-table-tag { text-align: left; }
	</style>
@endpushOnce

<div
	id="size-lbox"
	class="lbox modal"
>
	<div class="modal-form measure-table">

		<x-icon-close class="modal_close lbox-close" />

		<h3 class="txt-15rem txt-semibold mar-b-1 brb2 br-blk">Taglie:</h3>
		<p class="">Se non vedi tutte le taglie scorri verso il basso.</p>

		<img
			src="{{ Vite::image('product/size-table.webp') }}"
			alt=""
			loading="lazy"
			class="mx-auto w-60"
		>

		<div class="size-table-grid mar-b-1">

			<div class="_empty"></div>

			<p class="txt-line text-neutral-heading font-semibold">Larghezza:</p>

			<p class="txt-line text-neutral-heading font-semibold">Altezza:</p>


			@foreach ($sizes as $size)
				@php
					$measure = $measures->where('size', $size->tag)->first();
				@endphp

				<p class="size-table-tag text-neutral-heading font-semibold">{{ strtoupper($measure->name) }}:</p>
				<p class="">{{ $measure->width }} cm</p>
				<p class="">{{ $measure->height }} cm</p>
			@endforeach

		</div>

		@if ($material)
			<p class="">Composizione: {{ $material->val }}</p>
		@endif
	</div>
</div>
