@php
    $data = $product->type->info?->grouppedData();
    $description = nl2br($product->type->info?->description);
@endphp

@if ($data || $description)
    <section class="border-border-400 bg-surface-400 rounded-3xl border p-7 max-sm:p-5">
        <x-heading variant="article" class="mb-5">Descrizione</x-heading>

        @if ($data)
            <div class="grid columns-[13rem] gap-x-8 gap-y-6">
                @foreach ($data as $label => $content)
                    <div>
                        <p class="text-sm">{{ $label }}</p>
                        <p class="text-heading-400 mt-1">{{ $content }}</p>
                    </div>
                @endforeach
            </div>
        @elseif ($description)
            <div>
                <p class="text-sm leading-6">{!! $description !!}</p>
            </div>
        @endif
    </section>
@endif
