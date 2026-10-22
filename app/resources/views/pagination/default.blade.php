@if ($paginator->hasPages())
	<nav
		class="pagination flex flex-wrap items-center justify-center gap-2"
		role="navigation"
		aria-label="Paginazione"
	>
		{{-- Previous --}}
		@if (!$paginator->onFirstPage())
			<a
				href="{{ $paginator->previousPageUrl() }}"
				class="pagination-page"
				aria-label="Pagina precedente"
				rel="prev"
			>
				<x-icon-keyboard-arrow-left class="size-4" />
			</a>
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
					<a
						href="{{ $url }}"
						@class([
							'pagination-page',
							'is-active' => $page == $paginator->currentPage(),
						])
						@if ($page == $paginator->currentPage()) aria-current="page" @endif
					>
						{{ $page }}
					</a>
				@endforeach
			@endif
		@endforeach

		{{-- Next --}}
		@if ($paginator->hasMorePages())
			<a
				href="{{ $paginator->nextPageUrl() }}"
				class="pagination-page"
				aria-label="Pagina successiva"
				rel="next"
			>
				<x-icon-keyboard-arrow-right class="size-4" />
			</a>
		@endif
	</nav>
@endif
