<x-user-dashboard.settings-section
    title="Dati profilo"
    text="Le informazioni associate al tuo account."
    icon="user"
    tone="primary"
>
    <form class="grid gap-5 md:grid-cols-2" wire:submit="save">
        @session('success')
            <x-alert type="success" class="md:col-span-2">{{ session('success') }}</x-alert>
            type="success">
        @endsession

        <div class="grid content-start gap-2">
            <x-label>Nome</x-label>
            <x-input class="w-full" type="text" wire:model="firstName" placeholder="Nome" />

            @error('firstName')
                <x-alert type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2">
            <x-label>Cognome</x-label>
            <x-input class="w-full" type="text" wire:model="lastName" placeholder="Cognome" />

            @error('lastName')
                <x-alert type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2 md:col-span-2">
            <x-label>E-mail</x-label>
            <x-input class="w-full" type="email" wire:model="email" placeholder="E-mail" required />

            @error('email')
                <x-alert type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="md:col-span-2">
            <x-button type="submit" size="sm">Salva modifiche</x-button>
        </div>
    </form>
</x-user-dashboard.settings-section>
