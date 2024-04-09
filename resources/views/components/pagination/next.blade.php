@props(['page'])

<x-pagination.page :$page>
    <span class="w-1/3 aspect-square">
        {!! embed_icon('navigation/next', ['fill-none']) !!}
    </span>
</x-pagination.page>
