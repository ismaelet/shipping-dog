@props(['title', 'viewName'])

<div class="flex gap-4 items-center">

    <a class="border rounded w-10 aspect-square hover:text-neutral-700 transition-colors"
        href="{{ route(str_replace('manage', 'list', $viewName)) }}">
        {!! embed_icon('navigation/previous', ['fill-none', 'h-1/3']) !!}
    </a>

    <x-dashboard.title-bar :$title />

</div>
