@if ($href)
    <p class="flex gap-3 font-heading font-light">
        <a href="{{ $href }}" class="contents hover:text-primary transition-colors">
            @if (isset($icon))
                <span class="embedded-svg w-5 h-5 text-primary flex justify-center">
                    {{ import_svg('icons/contacts/' . $icon) }}
                </span>
            @endif

            {{ $slot }}
        </a>
    </p>
@else
    <p class="font-heading font-light">
        {{ $slot }}
    </p>
@endif
