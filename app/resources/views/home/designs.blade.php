@php
	$perPage = 4;
	$pages = (int) ceil($designs->count() / $perPage);
@endphp

<section class="dark:bg-surface full-width mt-12 bg-[#f1f1f1] py-16">
	<div class="content-grid">
		{{-- Heading --}}
		<div class="mb-8 grid items-center gap-2 sm:grid-cols-[1fr_auto]">
			<x-typography.section-heading class="section-heading">
				Design popolari
			</x-typography.section-heading>

			<a
				href="{{ route('shop') }}"
				class="text-secondary text-base font-medium sm:text-right"
			>
				Esplora tutti
			</a>
		</div>

		{{-- Carousel --}}
		<div class="designs-carousel relative min-w-0">
			<div
				class="designs-carousel-track gap-x-(--carousel-gap) grid snap-x snap-mandatory auto-cols-[calc((100%-(var(--carousel-columns)-1)*var(--carousel-gap))/var(--carousel-columns))] grid-flow-col overflow-x-auto overscroll-x-contain scroll-smooth [--carousel-columns:1.8] [--carousel-gap:min(3rem,6vw)] sm:[--carousel-columns:2.3] lg:[--carousel-columns:3.2] xl:overflow-x-hidden xl:[--carousel-columns:4]"
			>
				@foreach ($designs as $design)
					<div class="min-w-0 snap-start">
						{{-- Design image --}}
						<a
							href="{{ $design->url() }}"
							class="block"
						>
							<img
								src="{{ $design->image() }}"
								alt="{{ $design->name }}"
								loading="lazy"
								class="aspect-square w-full rounded-lg object-cover shadow-[0_1px_2px_rgba(0,0,0,0.1)]"
							/>
						</a>

						{{-- Design name --}}
						<h3 class="mt-4 truncate font-semibold">
							<a
								href="{{ $design->url() }}"
								class="hover:text-primary-400 transition-colors"
							>
								{{ $design->name }}
							</a>
						</h3>

						{{-- Seller --}}
						<p class="text-(--text-color) truncate">
							by
							<a
								href="{{ $design->seller->url() }}"
								class="hover:text-primary-400 transition-colors"
							>
								{{ $design->seller->name }}
							</a>
						</p>

						{{-- Product count --}}
						<p class="text-(--clr-2) mt-2 font-semibold">
							<a
								href="{{ $design->url() }}"
								class="hover:text-primary-400 transition-colors"
							>
								{{ $design->productCount() }} prodotti
							</a>
						</p>
					</div>
				@endforeach
			</div>

			{{-- Pagination --}}
			@if ($pages > 1)
				<div
					class="mt-8 hidden items-center justify-center gap-4 xl:flex"
					aria-label="Pagine design popolari"
				>
					@for ($i = 0; $i < $pages; $i++)
						<button
							type="button"
							class="designs-carousel-dot group grid size-4 cursor-pointer place-items-center"
							data-page="{{ $i }}"
							aria-label="Vai alla pagina {{ $i + 1 }}"
							aria-current="{{ $i === 0 ? 'true' : 'false' }}"
						>
							<span
								class="size-2.25 bg-(--surface-tertiary) group-aria-current:bg-(--text-color) rounded-full transition-colors"
							></span>
						</button>
					@endfor
				</div>
			@endif
		</div>
	</div>
</section>

@pushOnce('styles')
	<style>
		.designs-carousel-track {
			scrollbar-width: none;
		}

		.designs-carousel-track::-webkit-scrollbar {
			display: none;
		}
	</style>
@endPushOnce


@pushOnce('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			document.querySelectorAll('.designs-carousel').forEach(carousel => {
				const track = carousel.querySelector('.designs-carousel-track');
				const items = [...track.children];
				const dots = [...carousel.querySelectorAll('.designs-carousel-dot')];

				if (!items.length || !dots.length) return;

				const perPage = 4;

				function setActivePage(page) {
					dots.forEach((dot, index) => {
						dot.setAttribute(
							'aria-current',
							index === page ? 'true' : 'false'
						);
					});
				}

				function goToPage(page) {
					const index = Math.min(page * perPage, items.length - 1);
					const item = items[index];

					if (!item) return;

					track.scrollTo({
						left: item.offsetLeft - items[0].offsetLeft,
						behavior: 'smooth',
					});

					setActivePage(page);
				}

				dots.forEach((dot, index) => {
					dot.addEventListener('click', () => goToPage(index));
				});

				let scrollFrame = null;

				track.addEventListener('scroll', () => {
					if (scrollFrame !== null) {
						cancelAnimationFrame(scrollFrame);
					}

					scrollFrame = requestAnimationFrame(() => {
						const itemWidth = items[0]?.getBoundingClientRect().width;

						if (!itemWidth) return;

						const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
						const itemIndex = Math.round(track.scrollLeft / (itemWidth + gap));

						setActivePage(
							Math.min(
								Math.round(itemIndex / perPage),
								dots.length - 1
							)
						);
					});
				}, {
					passive: true
				});
			});
		});
	</script>
@endPushOnce
