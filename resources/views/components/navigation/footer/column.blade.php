<div class="grid content-start gap-4">
    <h3 class="font-heading text-lg font-semibold tracking-widest uppercase border-l-[4px] border-secondary pl-2">
        {{ $title }}</h3>

    @foreach ($data as $link)
        @if (isset($link['social_media']))
            <x-navigation.footer.link :href="config('custom.social_media.' . $link['social_media'] . '.url')" :icon="isset($link['icon']) ? $link['social_media'] : null">
                {{ config('custom.social_media.' . $link['social_media'] . '.label') }}
            </x-navigation.footer.link>
        @else
            <x-navigation.footer.link :href="isset($link['url']) ? route($link['url']) : null">
                {{ $link['label'] }}
            </x-navigation.footer.link>
        @endif
    @endforeach
</div>
