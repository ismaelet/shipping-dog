@props(['value' => ''])

<form
    action="{{ route('search') }}"
    method="GET"
    role="search"
    {{ $attributes->class('mx-auto flex w-full max-w-2xl items-stretch') }}
>
    <div class="group bg-surface-400 focus-within:ring-primary-400 flex min-w-0 flex-1 items-center rounded-l-full pr-1 pl-4 ring-2 ring-transparent transition-shadow">
        <span class="text-neutral-text group-focus-within:text-primary-400 size-5 shrink-0" aria-hidden="true"
            ><x-icon-search
        /></span>

        <input
            type="search"
            name="search"
            value="{{ $value }}"
            placeholder="Cerca design, negozio o tag"
            maxlength="30"
            aria-label="Cerca design, negozio o tag"
            class="text-neutral-heading [&::-webkit-search-cancel-button]:hidden min-w-0 flex-1 border-none bg-transparent px-3 py-3 outline-none"
        />

        @if ($value !== '')
            <a
                href="{{ route('search') }}"
                aria-label="Cancella ricerca"
                class="text-neutral-text hover:text-neutral-heading focus-visible:outline-primary-400 grid size-9 shrink-0 place-items-center rounded-full focus-visible:outline-2"
            >
                <x-icon-close class="size-4" />
            </a>
        @endif
    </div>

    <x-button type="submit" width="auto" class="rounded-l-none"> Cerca </x-button>
</form>
