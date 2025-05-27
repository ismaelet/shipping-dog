<?php

use App\Legacy\System\DB;

$measures = $product->type->measures;
?>

@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/product-measure-table.css') }}"
	>
@endpushOnce

@if ($sizeTable)
	<div
		id="size-lbox"
		class="lbox modal"
	>
		<div class="modal-form">

			<div class="modal_close lbox-close">
				{!! embed_icon('close') !!}
			</div>

			<h3 class="txt-15rem txt-semibold mar-b-1 brb2 br-blk">Taglie:</h3>
			<p class="">Se non vedi tutte le taglie scorri verso il basso.</p>

			<img
				src="{{ Vite::image('product/size-table.webp') }}"
				alt=""
				loading="lazy"
				class="mx-auto w-[15rem]"
			>

			<div class="size-table-grid mar-b-1">

				<div class="_empty"></div>

				<p class="txt-line font-semibold text-neutral-700">Larghezza:</p>

				<p class="txt-line font-semibold text-neutral-700">Altezza:</p>

				@foreach ($measures as $measure)
					<p class="size-table-tag font-semibold text-neutral-700">{{ strtoupper($measure->name) }}:</p>
					<p class="">{{ $measure->width }} cm</p>
					<p class="">{{ $measure->height }} cm</p>
				@endforeach

			</div>

			<?php if (DB::exists('items_types_measures WHERE type_id=? AND size=?', [$product->type->id, 'mat'])) { ?>
			<p class="">Composizione:
				<?= DB::find('val FROM items_types_measures WHERE type_id=? AND size=?', [$product->type->id, 'mat']) ?>
			</p>
			<?php } ?>

		</div>
	</div>
@endif
