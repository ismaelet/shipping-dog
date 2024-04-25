@push('styles')
    <style>

    </style>
@endpush

<x-templates.auth :$title welcome-image="auth/register-background.webp"
    welcome-title="<span class='font-light'>Registrati su</span><br>Shipping Dog"
    welcome-description="Registrandoti sulla nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli
	artisti che ti piacciono, aggiungere articoli ai tuoi preferiti e molto altro. Sei un disegnatore?
	Qui potrai vendere le tue grafiche stampate su tantissimi capi e accessori.">

    <!-- Form -->
    <x-slot name="formBody">
        <x-form.field>
            <x-form.label>Username</x-form.label>
            <x-form.input type="text" name="username" :value="old('username')" placeholder="Username" maxlength="20" required />

            @error('username')
                <x-form.input-warning>{{ $message }}</x-form.input-warning>
            @enderror
        </x-form.field>


        <x-form.field>
            <x-form.label>Password</x-form.label>
            <x-form.input type="password" name="password" placeholder="Password" maxlength="20" required />

            @error('password')
                <x-form.input-warning>{{ $message }}</x-form.input-warning>
            @enderror
        </x-form.field>

        <x-form.field>
            <x-form.input type="password" name="password_confirmation" placeholder="Conferma la password" maxlength="20"
                required />
        </x-form.field>

        <x-form.field>
            <x-form.label>E-mail</x-form.label>
            <x-form.input type="email" name="email" :value="old('email')" placeholder="E-mail" maxlength="100"
                required />

            @error('email')
                <x-form.input-warning>{{ $message }}</x-form.input-warning>
            @enderror
        </x-form.field>

        <x-form.checkbox name="privacy"
            text="Ho letto e acconsento al <a class='font-bold text-neutral-700' href='/infos/privacy'>trattamento
                dei dati</a>"
            required />
    </x-slot>

    <x-slot name="formFooter">
        <x-inputs.button type="submit">Registrati</x-inputs.button>
        <x-inputs.button href="{{ route('login') }}" variant="secondary">{{ __('Back') }}</x-inputs.button>
    </x-slot>
</x-templates.auth>
