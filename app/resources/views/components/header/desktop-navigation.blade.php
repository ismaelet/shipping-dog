@props([
    'links' => [],
    'location' => null,
])

<nav
	aria-label="Navigazione principale"
	class="hidden gap-12 xl:flex"
>
	@foreach ($links as $link)
		<div class="group relative">
			<a
				href="{{ $link['url'] }}"
				@class([
					'relative flex h-20 items-center gap-2 font-semibold text-neutral-heading transition-colors duration-100 hover:text-primary-500',
					'text-primary-400' => isset($link['loc']) && $location === $link['loc'],
					'text-neutral-heading' =>
						!isset($link['loc']) || $location !== $link['loc'],
				])
				@if (isset($link['loc']) && $location === $link['loc']) aria-current="page" @endif
			>
				{{ $link['title'] }}

				@if (isset($link['dropdown']))
					<x-icon-arrow-simple-down class="w-4 shrink-0" />
				@endif

				@if (!empty($link['new']))
					<span class="absolute right-0 top-1/2 -translate-y-full translate-x-[70%] text-red-500 [&_svg]:size-[1.2rem]">
						<x-icon-discount />
					</span>
				@endif
			</a>

			@if (isset($link['dropdown']))
				<div
					class="header-dropdown bg-primary-400 w-57.5 group-hover:max-h-75 group-focus-within:max-h-75 light absolute left-1/2 z-10 max-h-0 -translate-x-1/2 overflow-hidden transition-[max-height]"
				>
					@foreach ($link['dropdown'] as $dropdownLink)
						<a
							href="{{ $dropdownLink[1] }}"
							class="hover:bg-primary-300 hover:text-neutral-heading block p-[1em] text-left text-sm font-medium leading-none text-white transition-colors"
						>
							{{ $dropdownLink[0] }}
						</a>
					@endforeach
				</div>
			@endif
		</div>
	@endforeach
</nav>

@pushOnce('styles')
	<style>
		.header-dropdown {
			top: calc(100% + 1px);
		}
	</style>
@endpushOnce
