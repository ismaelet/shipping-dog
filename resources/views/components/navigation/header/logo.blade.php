@push('styles')
    <style>
        .main-header__logo {
            width: 3rem;
            height: auto;

            [data-local-theme="dark"] & .fc-700 {
                fill: currentColor;
            }
        }
    </style>
@endpush

<a href="{{ route('home') }}" class="main-header__logo">
    {{ import_svg('logo/logo-header') }}
</a>
