@pushOnce('styles')
    <style>
        main .input {
            padding-inline: 1.5em;
            border-radius: var(--border-radius-1000);
        }
    </style>
@endpushOnce

<x-templates.auth :$title welcome-title="<span class='font-light'>Benvenuto su</span><br>Shipping Dog"
    welcome-image="auth/login-background.webp"
    welcome-description="Entrando nella nostra piattaforma potrai monitorare lo stato dei tuoi ordini, seguire gli artisti
	che ti piacciono e molto altro. Sei un disegnatore? Qui
	potrai vendere le tue grafiche stampate su tantissimi capi e accessori.">

    <x-slot name="formBody">
        <x-form.input type="hidden" name="url" :value="old('url')" class="rounded-lg" />
        <x-form.input type="text" name="login" :value="old('login')" placeholder="Username o E-mail" required />
        <x-form.input type="password" name="password" placeholder="Password" required />

        @session('error')
            <p class="error ">{{ $value }}</p>
        @endsession

        <div class="flex items-center justify-between">
            <x-form.checkbox name="remember" text="Resta connesso" checked />

            <a href="/recover" class="text-right hover:text-neutral-700 transition-colors">
                <p class="">Hai dimenticato la password?</p>
            </a>
        </div>
    </x-slot>

    <x-slot name="formFooter">
        <x-form.button type="submit">Log In</x-form.button>
        <x-form.button :href="route('register')" variant="secondary">Registrati</x-form.button>
    </x-slot>
</x-templates.auth>
