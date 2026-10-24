@use('App\Helpers\FileHelper')
@use('App\Models\Ecommerce\Product')
@use('App\Models\Ecommerce\Product\ProductMockup')
@use('App\Models\Ecommerce\Product\ProductTypeMockupSize')

@php
	$tabs = [];
	$product = $productData['product'];
	$type = $productData['type'];

	if (!$product) {
	    $colorIds = $type->colors->pluck('id');
	    $colorTags = $type->colors->pluck('tag');
	    $positions = implode(',', $type->positions->pluck('id')->toArray());

	    $color_id = $colorIds->first();
	    $availableColorSlugs = implode(',', $colorTags->toArray());

	    // Create the item
	    $product = Product::create([
	        'name' => $design->name,
	        'design_id' => $design->id,
	        'user_id' => $design->user_id,
	        'type_id' => $type->id,
	        'color_id' => $color_id,
	        'colors' => $availableColorSlugs,
	        'positions' => $positions,
	    ]);
	}

	if ($product->type->colors->count() > 1) {
	    $tabs[] = ['Colori', 'colors'];
	}

	foreach ($product->type->positions as $position) {
	    if ($position->editable) {
	        $tabs[] = [$position->name, 'position', $position];
	    }
	}

	$designLight = FileHelper::storageAsset('/designs/' . $design->id . '.png');

	$designDarkPath = 'designs/' . $design->id . '_dark.png';
	$designDark = file_exists(base_path() . '/public/storage/' . $designDarkPath)
	    ? FileHelper::storageAsset($designDarkPath)
	    : $designLight;
@endphp

@pushOnce('scripts')
	<script>
		function refreshPreviewImage(productId) {
			const productPanel = document.querySelector(`[data-product-id="${productId}"]`);
			const previewImages = productPanel.querySelectorAll(`[data-preview-image]`);

			return axios
				.get(`/api/products/${productId}`, {
					params: {
						image_size: 1024,
					}
				})
				.then((response) => {
					const image = response.data.image

					previewImages.forEach((previewImage) =>
						previewImage.src = image
					)
				})
				.catch((error) => {
					console.log(error)
					return error
				})
		}
	</script>
@endpushOnce

<div
	id="item-<?= $product->type->id ?>"
	class="item <?= $product->status ? 'active' : '' ?>"
	data-product-id="{{ $product->id }}"
>

	@include('ecommerce.designs.edit.product.box')

	<div
		class="item-panel"
		data-id=<?= $product->id ?>
	>
		<div class="item-panel-close absolute inset-0"></div>

		<div class="box relative">

			@include('ecommerce.designs.edit.product.tabs')

			<div class="wrapper surface bg-background-400 py-6">

				<div class="close-icon item-panel-close icon text-neutral-heading cursor-pointer"><x-icon-close /></div>

				<div class="content px-6">

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
