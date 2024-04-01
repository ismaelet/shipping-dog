@push('styles')
    <style>
        .slider {
            grid-column: full-width;
            background-color: #aaa;
        }

        .slider__background {
            filter: saturate(1.4);
        }

        .slider__content {
            grid-template-areas:
                'contacts body scroller';
            grid-template-columns: max-content 1fr max-content;
        }

        .slider__contacts {
            grid-area: contacts;
        }

        .slider__body {
            grid-area: body;
        }

        .slider__scroller {
            grid-area: scroller;
        }
    </style>
@endpush

<div class="slider grid stack-content min-h-full" data-local-theme="dark">

    {{-- Background --}}
    <div class="slider__background opacity-50" style="{{ set_background_image('home/slider-background.webp') }}"></div>

    <div class="slider__content grid items-center min-h-full px-8 relative">
        {{-- Contacts --}}
        <aside class="slider__contacts grid gap-8 w-8">
            <div class="slider-bar"></div>
            <a href="" class="slider__contact max-h-8">
                {{ import_svg('icons/contacts/facebook') }}
            </a>
        </aside>

        {{-- Body --}}
        <div class="slider__body grid gap-20 py-16 text-center justify-items-center">
            <h1 class="text-main-heading">Studio Collaborando</h1>
            <p class="text-sub-main-heading">Sara Sperandio<br>laboratori e psicologia</p>
            <a href="#bio" class="button">Esplora</a>
        </div>

        {{-- Scroller --}}
        <aside class="slider__scroller grid gap-8">
            <a href="#bio">
                <p>esplora</p>
                <div class="slider-bar"></div>
            </a>
        </aside>
    </div>
</div>
