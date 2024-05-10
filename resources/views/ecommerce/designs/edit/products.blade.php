@use('App\Models\Ecommerce\Product')
@use('App\Models\Ecommerce\Product\ProductType')

@php
	$products = [];
	$productRecords = Product::where('design_id', $design->id)
	    ->with('color', 'position', 'type')
	    ->get();

	if (!$design->custom) {
	    $types = ProductType::where('hidden', 0)->orderBy('category')->orderBy('sort')->orderBy('id')->get();

	    $types->each(function ($type) use (&$products, $productRecords) {
	        $products[] = $productRecords->where('type_id', $type->id)->first();
	    });
	}
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

		html[data-color-scheme='dark'] .item-panel:before {
			background: rgba(15, 15, 15, 0.9);
		}

		.item-panel.active {
			opacity: 1;
			visibility: visible;
		}

		.item-panel .wrapper {
			background-color: var(--bgr);
			border-radius: 0 3px 3px 3px;
			padding: 2rem;
		}

		html[data-color-scheme='dark'] .item-panel .wrapper {
			background-color: var(--color-surface-400);
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
			background-color: #ddd;
			border-radius: 3px 3px 0 0;
			color: var(--color-neutral-300);
			font-size: 0.8rem;
			letter-spacing: 0.2em;
			line-height: var(--tabs-height);
			text-align: center;
			cursor: pointer;
		}

		html[data-color-scheme='dark'] .item-panel .tab {
			background-color: var(--color-surface-500);
		}

		.item-panel .tab.active {
			background-color: var(--bgr);
			color: var(--theme-max);
			cursor: default;
		}

		html[data-color-scheme='dark'] .item-panel .tab.active {
			background-color: var(--color-surface-400);
		}

		.item-panel .section {
			display: none;
			grid-gap: 2rem;
			align-items: center;
		}

		.item-panel .section.active {
			display: grid;
		}

		.item-panel .image {
			width: 100%;
			padding-bottom: 100%;
			border-radius: 3px;
			overflow: hidden;
			transition: filter 0.4s;
		}

		.item-panel .disabled .image {
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

		html[data-color-scheme='dark'] .item-panel .inp {
			background-color: var(--color-surface-500);
		}

		.item-panel .inp p {
			color: var(--color-neutral-700);
		}

		.item-panel .inp-box {
			background-color: var(--color-surface-400);
		}

		html[data-color-scheme='dark'] .item-panel .inp-box {
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
			background-color: var(--clr-1);
			border-radius: 30px;
			height: 1rem;
		}

		.item-panel .toggler .circle {
			background-color: var(--clr-1);
			border-radius: 50%;
			width: 1.8rem;
			aspect-ratio: 1 / 1;
			position: absolute;
			top: 50%;
			left: 100%;
			transform: translate(-100%, -50%);
			transition: left 0.4s, transform 0.4s;
		}

		.item-panel .disabled .toggler .circle {
			left: 0;
			transform: translate(0, -50%);
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

		.item-panel .disabled .toggler .off-text {
			display: inline-block;
		}

		.item-panel .disabled .toggler .on-text {
			display: none;
		}

		.item-panel .disabled .btn-box {
			display: none;
		}
	</style>
@endpushOnce

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const panelOpeners = document.querySelectorAll('.item-panel-open')
			const panelCloser = document.querySelectorAll('.item-panel-close')

			/*
						panelOpeners.forEach(element => {
							element.addEventListener('click', function() {
								const box = element.closest('.item');

								if (box.classList.contains('active')) {
									document.body.classList.add('fixed');
									box.querySelector('.item-panel').classList.add('active');
								}
							})
						});*/

			panelCloser.forEach(element => {
				element.addEventListener('click', function() {
					document.body.classList.remove('fixed');

					this.closest('.item-panel').classList.remove('active');
				});
			});

			/*

			$('body').on('click', '.item-panel .tab', function() {
				const self = $(this)
				const id = self.data('id')
				const panel = self.parents('.item-panel')

				panel.find('.tab').removeClass('active')
				self.addClass('active')

				panel.find('.section').removeClass('active')
				panel.find('.section' + id).addClass('active')
			})

			// mockup position and size
			$('body').on('change', '.mockup-size', function() {
				const self = $(this)
				const product = self.parents('.item')
				const section = self.parents('.section')
				const imagePosition = section.data('image-position')
				const design = product.find('[data-image-position="' + imagePosition + '"] .design')
				const size = self.val()
				const land = self.data('land')

				if (land === 1) {
					design.css('background-size', size + '% ' + 'auto')
				} else {
					design.css('background-size', 'auto ' + size + '%')
				}

				self.next().html(size + ' %')
			})

			$('body').on('change', '.mockup-y', function() {
				const self = $(this)
				const product = self.parents('.item')
				const section = self.parents('.section')
				const imagePosition = section.data('image-position')
				const design = product.find('[data-image-position="' + imagePosition + '"] .design')
				const x = section.find('.mockup-x').val()
				const y = self.val()

				design.css('background-position', x + '% ' + y + '%')

				self.next().html(y + ' %')
			})

			$('body').on('change', '.mockup-x', function() {
				const self = $(this)
				const product = self.parents('.item')
				const section = self.parents('.section')
				const imagePosition = section.data('image-position')
				const design = product.find('[data-image-position="' + imagePosition + '"] .design')
				const x = self.val()
				const y = section.find('.mockup-y').val()

				design.css('background-position', x + '% ' + y + '%')

				self.next().html(x + ' %')
			})

			$('body').on('change', '.mockup-inp input', function() {
				const designBox = $(this).parents('.item-panel').find('.design-box')

				clearTimeout(timer)

				designBox.addClass('active')

				timer = setTimeout(function() {
					designBox.removeClass('active')
				}, 2000)
			})

			// position toggler
			$('body').on('click', '.item-panel .toggler__button', async function() {
				const section = $(this).parents('.section')
				const sectionColors = section.parent().find('.section.colors')
				const enabled = section.data('enabled') ? 0 : 1
				const id = section.parents('.item-panel').data('id')

				var positions = []

				section.data('enabled', enabled)
				section.find('.js-enabled').data('enabled', enabled)
				section.toggleClass('disabled')

				if (enabled) {
					section.find('input').prop('disabled', false)
				} else {
					section.find('input').prop('disabled', true)
				}

				section
					.parent()
					.find('.section.positions .js-enabled')
					.each(function() {
						const self = $(this)
						const position = self.data('position')
						const enabled = self.data('enabled')

						if (enabled) {
							positions.push(position)
						}
					})

				if (positions.length) {
					await call_class_method('Legacy\\Classes\\Ecommerce\\Product', 'updatePositionIds',
						'[' + positions.join(',') + ']', id, null, true)
				}

				if (positions == '') {
					section.parents('.item').removeClass('active')
					sectionColors.addClass('disabled')
					sectionColors.find('input').prop('disabled', true)
				} else {
					section.parents('.item').addClass('active')
					sectionColors.removeClass('disabled')
					sectionColors.find('input').prop('disabled', false)
				}
			})*/
		})
	</script>
@endpushOnce

<div class="columns-15rem grid gap-8">
	@foreach ($products as $product)
		@include('ecommerce.designs.edit.product', ['product' => $product])
	@endforeach
</div>
