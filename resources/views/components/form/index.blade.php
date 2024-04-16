@push('styles')
    <style>
        main {
            --content-width: 60ch;
        }

        .form-title {
            font-size: 2.5rem;
        }

        .form-body {
            border-block: var(--border);
        }
    </style>
@endpush

<x-layouts.main :title="$title">
    <form class="form grid content-center gap-8 py-16" action="" method="POST" enctype="multipart/form-data">
        @csrf

        <x-typography.section-heading class="form-title text-center">{{ $title }}</x-typography.section-heading>

        <div class="form-body py-4 grid gap-4">
            {{ $body }}
        </div>

        <div class="form-footer grid gap-4">
            {{ $footer }}
        </div>
    </form>
</x-layouts.main>
