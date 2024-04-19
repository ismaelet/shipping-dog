@props(['title'])

<article class="grid grid-rows-[max-content]">
    @isset($title)
        <h3 class="font-semibold pl-8 pb-2 text-xl">{!! $title !!}</h3>
    @endisset

    <div {{ $attributes->class(['surface p-8 grid content-start gap-4']) }}>
        {{ $slot }}
    </div>
</article>
