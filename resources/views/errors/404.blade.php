@push('styles')
    <style>
        .page-background {
            opacity: 0.3;
        }
    </style>
@endpush

<x-templates.empty>
    <div class="page-content grid-flow stack-content aling-content-center justify-items-center fa-center">
        <div class="page-background fill-all background-fill"></div>

        <div class="page-body grid-flow center-content" data-spacing="400">
            <h1 class="title">Errore 404</h1>
            <p class="section-subtitle">Pagina non trovata</p>
            <div>
                <a class="button button--secondary button--auto-width" href="{{ route('home') }}">Torna alla home</a>
            </div>
        </div>
    </div>
</x-templates.empty>
