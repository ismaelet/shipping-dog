<div id="header-search" class="header-search border-border-400 bg-background-400 border-b" aria-hidden="true">
    <form action="{{ route('search') }}" method="GET" role="search" class="mx-auto max-w-2xl px-5 py-4">
        <div class="bg-surface-400 flex items-center gap-3 rounded-full px-5 py-3 shadow-sm">
            <span class="text-heading-400 size-4">
                <x-icon-search />
            </span>

            <input
                id="header-search-input"
                type="search"
                name="search"
                placeholder="Cerca prodotti, artisti, collezioni…"
                aria-label="Cerca prodotti"
                autocomplete="off"
                class="text-heading-400 w-full min-w-0 bg-transparent text-sm outline-none placeholder:text-slate-500"
            />
        </div>
    </form>
</div>
