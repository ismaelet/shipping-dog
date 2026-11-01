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
        @if ($paginator->currentPage() > 1)
            <button
                type="button"
                wire:click="previousPage"
                wire:loading.attr="disabled"
                class="pagination-page"
                aria-label="Pagina precedente"
            >
                <x-icon-keyboard-arrow-right class="size-4 rotate-180" />
            </button>

            <button
                type="button"
                wire:click="setPage(1)"
                wire:loading.attr="disabled"
                class="pagination-page"
                aria-label="Vai alla prima pagina"
            >
                <span aria-hidden="true"><x-icon-double-arrow class="size-4 rotate-180" /></span>
            </button>
        @endif

        @if ($windowStart > 1)
            {{-- <button
                type="button"
                wire:click="setPage(1)"
                wire:loading.attr="disabled"
                class="pagination-page hidden sm:block"
            >
                1
            </button> --}}

            @if ($windowStart > 2)
                <span
                    class="shop-pagination-ellipsis hidden size-8 items-center justify-center sm:flex"
                    aria-hidden="true"
                >...</span>
            @endif
        @endif

        @for ($page = $windowStart; $page <= $windowEnd; $page++)
            <button
                type="button"
                wire:click="setPage({{ $page }})"
                wire:loading.attr="disabled"
                @class(['pagination-page', 'is-active' => $page === $currentPage])
                @if ($page === $currentPage) aria-current="page" disabled @endif
            >
                {{ $page }}
            </button>
        @endfor

        @if ($windowEnd < $lastPage)
            @if ($windowEnd < $lastPage - 1)
                <span
                    class="shop-pagination-ellipsis hidden size-8 items-center justify-center sm:flex"
                    aria-hidden="true"
                >...</span>
            @endif

            {{-- <button
                type="button"
                wire:click="setPage({{ $lastPage }})"
                wire:loading.attr="disabled"
                class="pagination-page hidden sm:block"
            >
                {{ $lastPage }}
            </button> --}}
        @endif

        @if ($paginator->hasMorePages())
            <button
                type="button"
                wire:click="nextPage"
                wire:loading.attr="disabled"
                class="pagination-page"
                aria-label="Pagina successiva"
            >
                <x-icon-keyboard-arrow-right class="size-4" />
            </button>

            <button
                type="button"
                wire:click="setPage({{ $lastPage }})"
                wire:loading.attr="disabled"
                class="pagination-page"
                aria-label="Vai all'ultima pagina"
            >
                <span aria-hidden="true"><x-icon-double-arrow class="size-4" /></span>
            </button>
        @endif
    </nav>
@endif
