@push('styles')
    <style>
        .page-content {
            .main-heading {
                font-size: min(4rem, 7vw);
            }

            .button {
                min-width: min(100%, 18rem);
            }

            @media (max-width: 480px) {
                .welcome-section {
                    display: none;
                }
            }

            @media (min-width: 1024px) {
                grid-template-columns: minmax(0, 3fr) minmax(20rem, 2fr);
            }
        }
    </style>
@endpush

<x-templates.main>
    <div class="page-content grid fill-width">
        <!-- Welcome panel -->
        <section class="welcome-section grid stack-content items-center justify-items-center"
            data-local-color-scheme="dark">
            <div class="background-fill" style="background-image: url({{ Vite::image($welcomeImage) }}">
            </div>

            <div class="max-w-2xl grid gap-8 px-8 py-16">
                <x-typography.main-heading>{!! $welcomeTitle !!}</x-typography.main-heading>
                <x-typography.paragraph class="text-neutral-700">{!! $welcomeDescription !!}</x-typography.paragraph>
            </div>
        </section>

        <!-- Form -->
        <form class="grid items-center justify-items-center" method="POST">
            @csrf

            <div class="grid gap-12 py-16 px-8 w-full max-w-xl grid-cols-1">
                <x-typography.section-heading class="text-center">{{ $title }}</x-typography.section-heading>

                <div class="grid gap-4">
                    {{ $formBody }}
                </div>

                <div class="grid gap-2 justify-items-center">
                    {{ $formFooter }}
                </div>
            </div>
        </form>
    </div>
</x-templates.main>
