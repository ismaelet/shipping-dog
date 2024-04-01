@push('styles')
    <style>
        .welcome__image {
            background-position: left;
            background-size: 150% auto;

            @media (min-width: 769px) {
                background-position: right;
                background-size: cover;
            }
        }
    </style>
@endpush

<x-templates.auth :$title>
    {{-- Body --}}
    <x-slot:body>
        <div class="grid gap-2 text-left">
            <x-form.label>Nome completo:</x-form.label>
            <x-form.input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Nome" required />

            @error('first_name')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror

            <x-form.input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Cognome" required />

            @error('last_name')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror
        </div>

        <div class="grid gap-2 text-left">
            <x-form.label>Indirizzo e-mail:</x-form.label>
            <x-form.input type="email" name="email" value="{{ old('email') }}" placeholder="Indirizzo e-mail"
                required />

            @error('email')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror
        </div>

        <div class="grid gap-2 text-left">
            <x-form.label>Password:</x-form.label>
            <x-form.input type="password" name="password" value="" placeholder="Password" required />
            <x-form.input type="password" name="password_confirmation" value="" placeholder="Conferma la password"
                required />

            @error('password')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror
        </div>
    </x-slot:body>

    {{-- Footer --}}
    <x-slot:footer>
        <x-form.button type="submit">Registrati</x-form.button>
        <x-form.button href="{{ route('login') }}" variant="secondary">Hai già un account?</x-form.button>
    </x-slot:footer>

    {{-- Welcome Section --}}
    <x-slot:welcome>
        <div class="welcome__image" style="{{ set_background_image('auth/register-image.webp') }}"></div>
    </x-slot:welcome>
</x-templates.auth>
