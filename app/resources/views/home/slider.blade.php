@pushOnce('styles')
	<link
		rel="stylesheet"
		href="{{ asset('css/legacy/home-slider.css') }}"
	>
@endpushOnce

@pushOnce('scripts')
	<script
		defer
		src="{{ asset('js/legacy/home-slider.js') }}"
	></script>
@endpushOnce

<section
	id="slider"
	data-count="<?= $slidesN = 3 ?>"
>

	<div class="content relative">

		<!-- Background images -->
		<div
			id="slider-background"
			class="absolute inset-0"
		>

			@for ($i = 1; $i <= $slidesN; $i++)
				<img
					src="{{ Vite::image('home/slide-' . $i . '.webp') }}"
					class="slide cover-background <?= $i == 1 ? 'active' : '' ?> absolute"
					data-id="<?= $i ?>"
				/>
			@endfor
		</div>

		<!-- Body -->
		<div
			id="slider-body"
			class="grid justify-items-center gap-12 text-center lg:justify-items-start lg:text-left"
		>
			<div class="grid gap-8">
				<h1 class="txt-wht uppercase">Shi<span class="text-primary">pp</span>ing<br>Dog</h1>
				<p class="stitle">T-shirt, abbigliamento e accessori personalizzati</p>
			</div>

			<x-button :href="route('shop')">
				{!! embed_icon('cart') !!}
				Shop Now
			</x-button>
		</div>
	</div>
</section>
