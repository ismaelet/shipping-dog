@php
	$slides = [Vite::image('home/slide-1.webp'), Vite::image('home/slide-2.webp'), Vite::image('home/slide-3.webp')];
@endphp

@pushOnce('styles')
	<style>
		.home-slider .home-slider-gradient {
			position: absolute;
			inset: 0;
			background: linear-gradient(to right,
					rgb(2 6 23 / 80%),
					rgb(2 6 23 / 25%) 50%,
					transparent 70%);
		}
	</style>
@endPushOnce

<section
	aria-label="Shipping Dog"
	class="home-slider fill-width max-w-420 px-spacing-padding dark mx-auto w-full p-3 sm:p-5"
>
	<div class="min-h-162.5 rounded-4xl sm:min-h-180 relative overflow-hidden bg-slate-900">
		{{-- Background images --}}
		<div
			class="absolute inset-0"
			aria-hidden="true"
		>
			@foreach ($slides as $slide)
				<img
					src="{{ $slide }}"
					alt=""
					@class([
						'home-slide absolute inset-0 size-full object-cover object-[center_35%] transition-opacity duration-3000',
						'opacity-100' => $loop->first,
						'opacity-0' => !$loop->first,
					])
					@if (!$loop->first) loading="lazy" @endif
				/>
			@endforeach

			{{-- Gradient overlay --}}
			<div class="home-slider-gradient"></div>
		</div>

		{{-- Content --}}
		<div class="min-h-162.5 sm:min-h-180 relative z-10 flex max-w-7xl items-end px-7 py-12 sm:px-12 sm:py-16 lg:px-20">
			<div class="max-w-2xl text-white">
				<div class="mb-5 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-orange-300">
					<x-icon-spark class="size-4" />
					<span>Nuove grafiche ogni settimana</span>
				</div>

				<h1 class="lg:text-900 text-5xl font-bold leading-[0.94] tracking-[-0.055em] sm:text-7xl">
					Indossa ciò<br>
					che ti distingue.
				</h1>

				<p class="mt-6 max-w-lg text-base leading-7 text-white/75 sm:text-lg">
					T-shirt, abbigliamento e accessori personalizzati creati da artisti e pensati per durare.
				</p>

				<x-button
					:href="route('shop')"
					class="mt-8 block w-fit"
				>
					Scopri le collezioni
					<x-icon-arrow />
				</x-button>
			</div>
		</div>
	</div>
</section>

@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			document.querySelectorAll('.home-slider').forEach(slider => {
				const slides = [...slider.querySelectorAll('.home-slide')];

				if (slides.length < 2) {
					return;
				}

				let activeIndex = 0;

				window.setInterval(() => {
					const currentSlide = slides[activeIndex];

					activeIndex = (activeIndex + 1) % slides.length;

					const nextSlide = slides[activeIndex];

					currentSlide.classList.replace('opacity-100', 'opacity-0');
					nextSlide.classList.replace('opacity-0', 'opacity-100');
				}, 6000);
			});
		});
	</script>
@endPushOnce
