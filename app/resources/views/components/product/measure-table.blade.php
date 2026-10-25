@php
    $measures = $product->type->measures->sortBy('id');
    $sizes = $product->type->sizes;
    $material = $measures->where('size', 'mat')->first();
@endphp

@pushOnce('styles')
    <style>
        .size-table-grid {
            display: grid;
            grid-template-columns: 0.5fr repeat(2, minmax(0, 1fr));
            text-align: center;
        }

        .size-table-grid > * {
            border-bottom: var(--spacing-border) solid var(--color-border-400);
            padding: 0.5rem 0.3rem;
            font-size: 0.8rem;
        }

        .size-table-tag {
            text-align: left;
        }
    </style>
@endPushOnce

<x-modal name="product-size-guide" title="Taglie:" size="lg" dark>
    <p>Se non vedi tutte le taglie scorri verso il basso.</p>

    <img src="{{ Vite::image('product/size-table.webp') }}" alt="" loading="lazy" class="mx-auto w-60" />

    <div class="size-table-grid mb-4">
        <div class="_empty"></div>

        <p class="text-heading-400 font-semibold">Larghezza:</p>

        <p class="text-heading-400 font-semibold">Altezza:</p>

        @foreach ($sizes as $size)
            @php
                $measure = $measures->where('size', $size->tag)->first();
            @endphp

            <p class="size-table-tag text-heading-400 font-semibold">{{ strtoupper($measure->name) }}:</p>
            <p>{{ $measure->width }} cm</p>
            <p>{{ $measure->height }} cm</p>
        @endforeach
    </div>

    @if ($material)
        <p>Composizione: {{ $material->val }}</p>
    @endif
</x-modal>
