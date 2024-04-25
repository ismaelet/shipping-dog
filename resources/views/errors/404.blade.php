@push('styles')
    <style>
        .page-background {
            opacity: 0.3;
        }
    </style>
@endpush

<x-templates.empty>
    <div class="grid gap-8 content-center justify-items-center">
        <x-typography.main-heading>Errore 404</x-typography.main-heading>
        <x-typography.main-subheading>Pagina non trovata</x-typography.main-subheading>
        <x-inputs.button href="{{ route('home') }}" variant="secondary" size="auto">Torna alla
            home</x-inputs.button>
    </div>
</x-templates.empty>
