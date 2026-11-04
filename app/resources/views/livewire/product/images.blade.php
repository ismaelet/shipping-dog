@pushOnce('styles')
    <style>
        .image-section {
            top: calc(var(--header-height) + 2rem);
        }
    </style>
@endpushOnce

<div id="left-panel" class="h-full max-lg:max-w-none lg:top-8">
    {{-- Preview image --}}
    <section class="image-section sticky grid w-full grid-cols-1 items-start gap-4 lg:sticky">
        {{-- Other images --}}
        @if (count($imagesUrls) > 1)
            <div class="other-images js-other-images row-start-2 flex justify-start gap-3">
                @foreach ($imagesUrls as $positionId => $image)
                    <label wire:key="preview-image-{{ $positionId }}">
                        <input
                            type="radio"
                            name="preview-position-id"
                            value={{ $positionId }}
                            class="hidden"
                            wire:model.live="previewPositionId"
                        />
                        <img
                            class="{{ $previewPositionId == $positionId ? 'outline-primary-400' : 'outline-transparent' }} aspect-square w-20 rounded-2xl border border-border-400 object-cover outline-2 -outline-offset-2 transition-colors cursor-pointer"
                            src="{{ $image }}"
                            alt=""
                        />
                    </label>
                @endforeach
            </div>
        @endif

        {{-- Main image --}}
        <div class="main-image max-w-full">
            <div id="item-img-box" class="stack-content relative grid aspect-square items-center justify-items-center">
                {{-- Image --}}
                <img
                    class="img-zoom img-fill js-main-image js-primary-image bg-surface-500 w-full rounded-3xl transition-all"
                    id="item-img"
                    src="{{ $mainImageUrl }}"
                    alt=""
                    wire:loading.class="opacity-50 saturate-0"
                />

                {{-- Logos on the image --}}
                @if ($product->logoGroup() && $product->logoGroup()->preview)
                    {{-- <img class="logo-field__preview-logo js-logo-image" alt="" /> --}}
                @endif

                {{-- Loading icon on image change --}}
                <x-svg-loading
                    wire:loading.class="opacity-100"
                    class="pointer-events-none w-16 opacity-0 transition-opacity"
                />

                {{-- Zoom icon on mobile --}}
                {{-- <div id="img-zoom-icon" class="img-zoom icon-box clickable">
                    <x-icon-search />
                </div> --}}

                {{-- Wishlist button --}}
                <div class="absolute top-5 right-5 z-2">
                    <livewire:wishlist-button
                        :product="$product"
                        :key="'wishlist-button-'.$product->id.'-'.$product->color_id.'-'.$product->position_id"
                    />
                </div>
            </div>
        </div>
    </section>
</div>
