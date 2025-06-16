@props(['direction' => 'right'])

@pushOnce('styles')
	<style>
		.info[data-direction="center"] {
			.info-text {
				translate: calc(-50%) 0;
			}
		}

		@media (max-width: 1024px) {
			.info[data-direction="mobile-left"] {
				.info-text {
					translate: calc(-100% + 2.5rem) 0;
				}
			}

			.info[data-direction="mobile-center"] {
				.info-text {
					translate: calc(-50%) 0;
				}
			}

			.info[data-direction="mobile-right"] {
				.info-text {
					translate: calc(100% - 2.5rem) 0;
				}
			}
		}
	</style>
@endpushOnce

<div
	class="info group relative w-max cursor-pointer"
	data-direction="{{ $direction }}"
>
	<div class="group-hover:text-primary grid aspect-square w-6 place-items-center rounded-full border transition-colors">
		<x-icon-question class="w-2/5" />
	</div>

	<p
		class="info-text surface-2 bg-background pointer-events-none absolute z-10 w-[30rem] max-w-[70vw] -translate-y-2 p-3 text-sm italic opacity-0 shadow transition-all group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100"
	>
		{{ $slot }}
	</p>
</div>
