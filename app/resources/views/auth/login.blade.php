@pushOnce('styles')
    <style>
        main .input {
            padding-inline: 1.5em;
            border-radius: var(--border-radius-1000)
        }
    </style>
@endpushOnce

<x-layouts.auth :$title welcome-title="<span class='font-light'>Benvenuto su</span><br>Shipping Dog"
    welcome-image="auth/login-background.webp"
    welcome-description="Entrando nella nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli artisti
	che ti piacciono e molto altro. Sei un disegnatore? Qui
	potrai vendere le tue grafiche stampate su tantissimi capi e accessori.">

    <x-slot name="formBody">
        <x-inputs.input type="hidden" name="url" :value="old('url')" />
        <x-inputs.input type="text" name="login" :value="old('login')" placeholder="Username o E-mail" required />
        <x-inputs.input type="password" name="password" placeholder="Password" required />

        @session('error')
            <x-inputs.warning>{{ $value }}</x-inputs.warning>
        @endsession

        <div class="flex items-center justify-between">
            <x-inputs.checkbox name="remember" text="Resta connesso" checked />

            <a href="/recover" class="text-right hover:text-neutral-700 transition-colors">
                <p class="">Hai dimenticato la password?</p>
            </a>
        </div>
    </x-slot>

    <x-slot name="formFooter">
        <x-inputs.button type="submit">Log In</x-inputs.button>
        <x-inputs.button :href="route('register')" variant="secondary">Registrati</x-inputs.button>
    </x-slot>
</x-layouts.auth>
