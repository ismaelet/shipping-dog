@pushOnce('styles')
    <style>
        @media (min-width: 1024px) {
            .page-content {
                grid-template-areas:
                    'guest login'
                    'register login'
                    'shop _';
            }

            .guest-panel {
                grid-area: guest;
            }

            .login-panel {
                grid-area: login;
            }

            .register-panel {
                grid-area: register;
            }

            .shop-panel {
                grid-area: shop;
            }
        }
    </style>
@endpushOnce

<x-layouts.cart>
    <div class="page-content grid items-start gap-8 lg:grid-cols-2">
        {{-- Guest --}}
        <form action="{{ route('cart.access.guest') }}" class="guest-panel card" method="POST">
            @csrf

            <x-heading variant="article">Ordina senza registrarti</x-heading>
            <p>Clicca qui se vuoi procedere all'acquisto senza registrarti</p>
            <x-button type="submit" name="guest">Ordina come ospite</x-button>
        </form>

        {{-- Login --}}
        <form
            class="login-panel card"
            method="POST"
            action="{{ route('login.auth', ['intended' => route('cart.address')]) }}"
        >
            @csrf

            <x-heading variant="article">Hai già un account?</x-heading>

            <x-form.field>
                <input type="hidden" name="redirect" value="true" />
                <x-input name="email" placeholder="Indirizzo e-mail" />
                <x-input type="password" name="password" placeholder="Password" />

                <div class="text-right">
                    <a class="hover:text-heading-400 transition-colors" href="{{ route('password.request') }}"
                        >Hai dimenticato la password?</a>
                </div>
            </x-form.field>

            @error('email')
                <x-alert type="error">{{ $message }}</x-alert>
            @enderror

            <div class="grid grid-cols-1 gap-2">
                <x-button type="submit"> Log In </x-button>

                <x-button
                    href="{{ route('auth.google.redirect', ['intended' => route('cart.address')]) }}"
                    variant="secondary"
                >
                    <x-icon-google />
                    Accedi con Google
                </x-button>
            </div>
        </form>

        {{-- Register --}}
        <div class="register-panel card">
            <x-heading variant="article">Registrati</x-heading>

            <p>Vai qui sotto se vuoi registrarti</p>

            <div class="grid grid-cols-1 gap-2">
                <x-button :href="route('register')" variant="secondary">Registrati</x-button>

                <x-button
                    href="{{ route('auth.google.redirect', ['intended' => route('cart.address')]) }}"
                    variant="secondary"
                >
                    <x-icon-google />
                    Registrati con Google
                </x-button>
            </div>
        </div>

        {{-- Continue --}}
        <div class="shop-panel card">
            <x-heading variant="article">Continua con gli acquisti</x-heading>

            <p>Vuoi aggiungere altri articoli al carrello prima di continuare?</p>

            <x-button :href="route('shop')" variant="secondary">Torna al negozio</x-button>
        </div>
    </div>
</x-layouts.cart>
