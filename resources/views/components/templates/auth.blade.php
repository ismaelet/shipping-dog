@push('styles')
    <style>
        .page__content {
            @media (min-width: 769px) {
                grid-template-areas: "welcome form";
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .welcome {

            @media (max-width: 768px) {
                display: grid;
                grid-column: full-width;
            }

            @media (min-width: 769px) {
                grid-area: welcome;
            }
        }

        .welcome__image {
            background-position: 100% 100%;
            background-size: cover;

            @media (max-width: 768px) {
                min-height: 25rem;
            }
        }

        .page__form {
            @media (min-width: 769px) {
                grid-area: form;
            }
        }
    </style>
@endpush

<x-templates.main :header-config="['floating' => true]">
    <div class="page__content full-width grid">
        <section class="welcome grid">
            {{ $welcome }}
        </section>

        <form method="POST" class="page__form grid content-center justify-items-center md:px-8 py-16 text-center">
            <div class="w-full max-w-md grid gap-16">
                <div>
                    <x-typography.main-heading>
                        {{ $title }}
                    </x-typography.main-heading>
                </div>

                <div class="grid gap-4">
                    @csrf

                    {{ $body }}
                </div>

                <div class="grid gap-4">
                    {{ $footer }}
                </div>
            </div>
        </form>
    </div>
</x-templates.main>
