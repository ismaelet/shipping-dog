@use('App\Models\Ecommerce\Product')
@use('App\Models\Ecommerce\Product\ProductType')

@php
	$products = [];
	$productRecords = Product::where('design_id', $design->id)
	    ->with('design', 'design_with_trashed', 'color', 'colors', 'position', 'type', 'type.colors', 'type.positions')
	    ->get();

	$types = ProductType::where('hidden', 0)
	    ->with('colors', 'positions')
	    ->orderBy('category')
	    ->orderBy('sort')
	    ->orderBy('id')
	    ->get();

	$types->each(function ($type) use (&$products, $productRecords) {
	    $products[] = [
	        'product' => $productRecords->where('type_id', $type->id)->first(),
	        'type' => $type,
	    ];
	});
@endphp

@pushOnce('styles')
	<style>
		.item-list {
			min-height: 7rem;
		}

		.item-img {
			aspect-ratio: 1;
			background-color: var(--color-surface-500);
			border-radius: 0.5rem;
			filter: saturate(0);
		}

		.item-img img {
			border-radius: 0.5rem;
		}

		.item.active .item-img {
			filter: none;
		}

		.item-over {
			opacity: 0.5;
			border-radius: 0.5rem;
			background-color: #eee;
		}

		.item.active .item-over {
			display: none;
		}

		.type-name {
			letter-spacing: 0.05em;
		}

		.item .img-buttons {
			grid-auto-columns: minmax(0, 1fr);
			grid-auto-flow: column;
			grid-gap: 5px;
		}

		.switch-btn {
			width: 100%;
			min-width: unset;
			font-size: 0.7rem;
			border-radius: 4px;
			padding-block: 0.5em !important;
		}

		/* Item panel */
		.item-panel {
			--tabs-height: 3rem;
			display: grid;
			grid-template-columns: minmax(auto, 1200px);
			width: 100%;
			visibility: hidden;
			opacity: 0;
			width: 100%;
			height: 100%;
			padding: 1rem;
			position: fixed;
			top: 0;
			left: 0;
			place-content: center;
			z-index: 100;
			transition: opacity 0.5s, visibility 0.5s;
		}

		.item-panel:before {
			content: '';
			background: rgba(150, 150, 150, 0.7);
			backdrop-filter: saturate(0);
			width: 100%;
			height: 100%;
			position: fixed;
			top: 0;
			left: 0;
		}

		.dark .item-panel:before {
			background: rgba(15, 15, 15, 0.9);
		}

		.item-panel.active {
			opacity: 1;
			visibility: visible;
		}

		.item-panel .wrapper {
			border-top-left-radius: 0;

			@media(max-width: 1025) {
				border-top-right-radius: 0;
			}
		}

		.item-panel .content {
			max-height: calc(100vh - 8rem - var(--tabs-height));
			overflow-x: hidden;
			overflow-y: auto;
		}

		.item-panel .close-icon {
			width: 1.2rem;
			height: 1.2rem;
			position: absolute;
			top: 1rem;
			right: 1rem;
		}

		.item-panel .tabs {
			display: grid;
			grid-auto-columns: minmax(auto, 13rem);
			grid-auto-flow: column;
			column-gap: 5px;
		}

		.item-panel .tab {
			background-color: var(--color-surface-500);
			border-radius: 3px 3px 0 0;
			border-color: var(--color-surface-500);
			color: var(--color-neutral-300);
			font-size: 0.8rem;
			letter-spacing: 0.2em;
			line-height: var(--tabs-height);
			text-align: center;
			cursor: pointer;
			translate: 0 var(--border-width);

			.dark & {
				background-color: var(--color-surface-400);
			}
		}


		.item-panel .tab.active {
			background-color: var(--color-background);
			cursor: default;
			color: var(--color-neutral-700);
			border-bottom: 0;
		}

		.item-panel .section {
			display: none;
			grid-gap: 2rem;
		}

		.item-panel .section.active {
			display: grid;
		}

		[data-position-panel][data-enabled="false"] .image {
			filter: saturate(0);
		}

		.item-panel .design-box {
			position: absolute;
			transition: background 0.5s, outline 0.5s;
		}

		.item-panel .design-box.active {
			background-color: rgba(255, 0, 0, 0.2);
			outline: 1px dashed red;
			transition: none;
		}

		.item-panel .design {
			display: none;
			width: 100%;
			height: 100%;
			position: absolute;
			top: 0;
			left: 0;
		}

		.item-panel .design.active {
			display: block;
		}

		.item-panel .data {
			display: grid;
			min-height: 100%;
			align-items: center;
		}

		.item-panel .inp {
			background-color: var(--color-surface-400);
			border-radius: 5px;
		}

		.dark .item-panel .inp {
			background-color: var(--color-surface-500);
		}

		.item-panel .inp p {
			color: var(--color-neutral-700);
		}

		.item-panel .inp-box {
			background-color: var(--color-surface-400);
		}

		.dark .item-panel .inp-box {
			background-color: var(--color-surface-500);
		}

		.mockup-inp {
			grid-template-columns: auto 4rem;
		}

		.item-panel .tip-icon-txt {
			right: -2rem;
		}

		.item-panel .toggler {
			--circle: 2rem;
			margin-top: 2rem;
		}

		.item-panel .toggler__button {
			display: inline-block;
			filter: saturate(1);
			width: 4rem;
			vertical-align: middle;
			cursor: pointer;
			transition: filter 0.4s;
		}

		.item-panel .disabled .toggler__button {
			filter: saturate(0);
		}

		.item-panel .toggler .line {
			opacity: 0.5;
			background-color: var(--color-primary-400);
			border-radius: 30px;
			height: 1rem;
		}

		.item-panel .toggler .circle {
			background-color: var(--color-primary-400);
			border-radius: 50%;
			width: 1.8rem;
			aspect-ratio: 1 / 1;
			position: absolute;
			top: 50%;
			left: 100%;
			transform: translate(-100%, -50%);
			transition: left 0.4s, transform 0.4s;
		}

		.item-panel .toggler p {
			display: inline-block;
			margin-left: 10px;
			vertical-align: middle;
			font-weight: 600;
		}

		.item-panel .toggler .off-text {
			display: none;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const panelOpeners = document.querySelectorAll('.item-panel-open')
			const panelCloser = document.querySelectorAll('.item-panel-close')

			panelOpeners.forEach(element => {
				element.addEventListener('click', function() {
					const box = element.closest('.item');

					if (box.classList.contains('active')) {
						box.querySelector('.item-panel').classList.add('active');
					}
				})
			});

			panelCloser.forEach(element => {
				element.addEventListener('click', function() {
					document.body.classList.remove('fixed');

					this.closest('.item-panel').classList.remove('active');
				});
			});

			document.querySelectorAll('.item-panel .tab').forEach(function(tab) {
				tab.addEventListener('click', function() {
					var self = this;
					var id = self.dataset.id;
					var panel = self.closest('.item-panel');

					panel.querySelectorAll('.tab').forEach(function(tab) {
						tab.classList.remove('active');
					});
					self.classList.add('active');

					panel.querySelectorAll('.section').forEach(function(section) {
						section.classList.remove('active');
					});
					panel.querySelector('.section' + id).classList.add('active');
				});
			});


			// mockup position and size
			document.querySelectorAll('[name="preview_size"]').forEach(function(element) {
				element.addEventListener('input', function() {
					const productPanel = this.closest('[data-product-id]');
					const positionPanel = this.closest('[data-panel]');
					const imagePosition = positionPanel.dataset.imagePosition;
					const landscape = this.dataset.landscape;
					const size = this.value;

					const designs = productPanel.querySelectorAll('[data-image-position="' +
						imagePosition + '"] .design');


					designs.forEach(function(design) {
						if (landscape == 'true') {
							design.style.backgroundSize = size + '% auto';
						} else {
							design.style.backgroundSize = 'auto ' + size + '%';
						}
					});

					showFrame(positionPanel)
				});
			});

			document.querySelectorAll('[name="preview_position_y"], [name="preview_position_x"]').forEach(function(
				element) {
				element.addEventListener('input', function() {
					const productPanel = this.closest('[data-product-id]');
					const positionPanel = this.closest('[data-panel]');
					const imagePosition = positionPanel.dataset.imagePosition;
					const x = positionPanel.querySelector('[name="preview_position_x"]').value;
					const y = positionPanel.querySelector('[name="preview_position_y"]').value;

					const designs = productPanel.querySelectorAll('[data-image-position="' +
						imagePosition + '"] .design');

					designs.forEach(function(design) {
						design.style.backgroundPosition = x + '% ' + y + '%';
					});

					showFrame(positionPanel)
				});
			});

			function showFrame(panel) {
				const designBox = panel.querySelector('.design-box');

				let timer

				clearTimeout(timer)

				designBox.classList.add('active')

				timer = setTimeout(function() {
					designBox.classList.remove('active')
				}, 2000)
			}
		})
	</script>
@endpushOnce

<div class="columns-15rem grid gap-8">
	@foreach ($products as $productData)
		@include('ecommerce.designs.edit.product', ['productData' => $productData])
	@endforeach
</div>
