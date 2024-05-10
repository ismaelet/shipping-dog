@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockup')

@php
	$tabs = [];

	if ($product->type->colors->count() > 1) {
	    $tabs[] = ['Colori', 'colors'];
	}

	/*foreach ($product->type->positions as $position) {
	    if ($position->editable) {
	        $tabs[] = [$position->name, 'position', $position];
	    }
	}*/

	$designLight = public_asset('/designs/' . $design->id . '.png');

	$designDarkPath = 'designs/' . $design->id . '_dark.png';
	$designDark = file_exists(base_path() . '/public/storage/' . $designDarkPath)
	    ? public_asset($designDarkPath)
	    : $designLight;
@endphp

<div
	id="item-<?= $product->type->id ?>"
	class="item <?= $product->status ? 'active' : '' ?>"
>

	@include('ecommerce.designs.edit.product.box')

	<div
		class="item-panel"
		data-id=<?= $product->id ?>
	>

		<div class="box relative">

			@include('ecommerce.designs.edit.product.tabs')

			<div class="wrapper">

				<div class="close-icon item-panel-close icon cursor-pointer text-neutral-700"><?= embed_icon('close') ?></div>

				<div class="content">

					@foreach ($tabs as $key => $tab)
						@if ($tab[1] == 'position')
							<!-- Position tabs -->
							@include('ecommerce.designs.edit.product.position-panel')
						@else
							<!-- Color tab -->
							@include('ecommerce.designs.edit.product.color-panel')
						@endif
					@endforeach

				</div>
			</div>
		</div>
	</div>
</div>
