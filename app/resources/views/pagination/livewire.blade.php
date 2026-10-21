@if ($paginator->hasPages())
	<nav
		class="pagination flex flex-wrap items-center justify-center gap-2"
		role="navigation"
		aria-label="Paginazione"
	>
		{{-- Previous --}}
		@if (!$paginator->onFirstPage())
			<button
				type="button"
				wire:click="previousPage"
				wire:loading.attr="disabled"
				class="pagination-page"
				aria-label="Pagina precedente"
			>
				<x-icon-previous class="size-4" />
			</button>
		@endif

		{{-- Pages --}}
		@foreach ($elements as $element)
			@if (is_string($element))
				<span class="flex size-8 items-center justify-center text-neutral-400">
					{{ $element }}
				</span>
			@endif

			@if (is_array($element))
				@foreach ($element as $page => $url)
					<button
						type="button"
						wire:click="setPage({{ $page }})"
						wire:loading.attr="disabled"
						@class([
							'pagination-page',
							'is-active' => $page == $paginator->currentPage(),
						])
						@if ($page == $paginator->currentPage()) aria-current="page"
                            disabled @endif
					>
						{{ $page }}
					</button>
				@endforeach
			@endif
		@endforeach

		{{-- Next --}}
		@if ($paginator->hasMorePages())
			<button
				type="button"
				wire:click="nextPage"
				wire:loading.attr="disabled"
				class="pagination-page"
				aria-label="Pagina successiva"
			>
				<x-icon-next class="size-4" />
			</button>
		@endif
	</nav>
@endif
