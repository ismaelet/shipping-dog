@props(['paginator'])

@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();

        $windowStart = match (true) {
            $lastPage <= 3, $currentPage <= 3 => 1,
            $currentPage >= $lastPage - 2 => max(1, $lastPage - 2),
            default => $currentPage - 1,
        };

        $windowEnd = min($lastPage, $windowStart + 2);
    @endphp

    <nav
        class="pagination shop-pagination flex flex-wrap items-center justify-center gap-2"
        role="navigation"
        aria-label="Paginazione"
    >
        {{-- Previous and first page --}}
        @if (! $paginator->onFirstPage())
            <a
                href="{{ $paginator->previousPageUrl() }}"
                class="pagination-page"
                aria-label="Pagina precedente"
                rel="prev"
            >
                <x-icon-keyboard-arrow-right class="size-4 rotate-180" />
            </a>

            <a href="{{ $paginator->url(1) }}" class="pagination-page" aria-label="Vai alla prima pagina">
                <span aria-hidden="true">
                    <x-icon-double-arrow class="size-4 rotate-180" />
                </span>
            </a>
        @endif

        {{-- First page and leading ellipsis --}}
        @if ($windowStart > 1)
            <a href="{{ $paginator->url(1) }}" class="pagination-page" aria-label="Vai alla pagina 1"> 1 </a>

            @if ($windowStart > 2)
                <span class="shop-pagination-ellipsis flex size-8 items-center justify-center" aria-hidden="true"
                    >...</span>
            @endif
        @endif

        {{-- Page window --}}
        @for ($page = $windowStart; $page <= $windowEnd; $page++)
            @if ($page === $currentPage)
                <span class="pagination-page is-active" aria-current="page"> {{ $page }} </span>
            @else
                <a href="{{ $paginator->url($page) }}" class="pagination-page" aria-label="Vai alla pagina {{ $page }}">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Trailing ellipsis and last page --}}
        @if ($windowEnd < $lastPage)
            @if ($windowEnd < $lastPage - 1)
                <span class="shop-pagination-ellipsis flex size-8 items-center justify-center" aria-hidden="true"
                    >...</span>
            @endif

            <a
                href="{{ $paginator->url($lastPage) }}"
                class="pagination-page"
                aria-label="Vai alla pagina {{ $lastPage }}"
            >
                {{ $lastPage }}
            </a>
        @endif

        {{-- Next and last page --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-page" aria-label="Pagina successiva" rel="next">
                <x-icon-keyboard-arrow-right class="size-4" />
            </a>

            <a href="{{ $paginator->url($lastPage) }}" class="pagination-page" aria-label="Vai all'ultima pagina">
                <span aria-hidden="true">
                    <x-icon-double-arrow class="size-4" />
                </span>
            </a>
        @endif
    </nav>
@endif
