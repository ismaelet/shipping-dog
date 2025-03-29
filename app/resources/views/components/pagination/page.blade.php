@props(['page', 'currentPage' => null])

@pushOnce('styles')
    <style>
        [data-color-scheme="light"] .pagination-page.active {
            color: #fff;
        }
    </style>
@endpushOnce

<button type="submit" name="page" value="{{ $page }}" @class([
    'pagination-page',
    'flex',
    'items-center',
    'justify-center',
    'rounded',
    'text-sm',
    'font-medium',
    'transition-colors',
    'disabled:pointer-events-none',
    'disabled:opacity-50',
    'border',
    'w-10',
    'h-10',
    'leading-10',
    'bg-primary text-neutral-700' => $page == $currentPage,
    'hover:text-primary' => $page != $currentPage,
    'active' => $page == $currentPage,
])>

    {{ $slot }}

</button>
