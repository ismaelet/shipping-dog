@props(['product', 'config', 'clothing', 'details', 'light', 'dark', 'landscape'])
<div class="stack-content relative grid aspect-square overflow-hidden rounded-lg">
    @if ($product->design->custom)
        <img src="{{ $product->image(1024) }}" alt="Anteprima prodotto" class="h-full w-full object-contain" />
    @elseif ($clothing)
        <img
            src="{{ Vite::image('product/mockup-background.png') }}"
            alt="Anteprima capo"
            class="h-full w-full object-cover"
        />
        <img src="{{ storage_asset($clothing) }}" alt="Anteprima capo" class="h-full w-full object-cover" />
        <div
            class="absolute"
            style="width: {{ $config->siz_x_max }}%; height: {{ $config->siz_y_max }}%; top: {{ $config->pos_y_min }}%; left: {{ $config->pos_x_min }}%"
        >
            <div
                class="h-full w-full bg-no-repeat"
                style="background-image: url('{{ $product->color?->dark ? $dark : $light }}')"
                :style="{ backgroundPosition: x + '% ' + y + '%', backgroundSize: {{ $landscape ? "size + '% auto'" : "'auto ' + size + '%'" }} }"
            ></div>
        </div>
        @if ($details)
            <img
                src="{{ storage_asset($details) }}"
                alt=""
                class="pointer-events-none relative h-full w-full object-cover"
            />
        @endif
    @else
        <p>Anteprima non disponibile.</p>
    @endif
</div>
