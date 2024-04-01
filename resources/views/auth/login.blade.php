<x-templates.auth :$title>
    {{-- Body --}}
    <x-slot:body>
        <div class="form-field">
            <x-form.open-input type="email" name="email" value="{{ old('email') }}" placeholder="Indirizzo e-mail"
                required />

            @error('email')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror
        </div>

        <div class="form-field">
            <x-form.open-input type="password" name="password" value="" placeholder="Password" required />

            @error('password')
                <x-form.error-warning>{{ $message }}</x-form.error-warning>
            @enderror
        </div>

        @if (session('status'))
            <x-form.error-warning class="text-left">{{ session('status') }}</x-form.error-warning>
        @endif
    </x-slot:body>

    {{-- Footer --}}
    <x-slot:footer>
        <x-form.button type="submit">Login</x-form.button>
        <x-form.button href="{{ route('register') }}" variant="secondary">Registrati</x-form.button>
    </x-slot:footer>

    {{-- Welcome Section --}}
    <x-slot:welcome>
        <div class="welcome__image" style="{{ set_background_image('auth/login-image.webp') }}"></div>
    </x-slot:welcome>
</x-templates.auth>
