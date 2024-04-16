@props(['title'])

<article>
    <h3 class="font-semibold pl-8 pb-2 text-xl">{{ $title }}</h3>
    <div class="surface p-8 grid gap-4">
        {{ $slot }}
    </div>
</article>
