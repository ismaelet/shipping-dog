<x-user-dashboard.settings-section
    title="Dati profilo"
    text="Le informazioni associate al tuo account."
    icon="user"
    tone="primary"
>
    <form class="grid gap-5 md:grid-cols-2" wire:submit="save">
        @session('success')
            <x-alert wire:key="profile-success" type="success" class="md:col-span-2">{{ session('success') }}</x-alert>
        @endsession

        <div class="grid content-start gap-2" wire:key="profile-first-name">
            <x-label>Nome</x-label>
            <x-input class="w-full" type="text" wire:model="firstName" placeholder="Nome" />

            @error('firstName')
                <x-alert wire:key="profile-first-name-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2" wire:key="profile-last-name">
            <x-label>Cognome</x-label>
            <x-input class="w-full" type="text" wire:model="lastName" placeholder="Cognome" />

            @error('lastName')
                <x-alert wire:key="profile-last-name-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2 md:col-span-2" wire:key="profile-email">
            <x-label>E-mail</x-label>
            <x-input class="w-full" type="email" wire:model="email" placeholder="E-mail" required />

            @error('email')
                <x-alert wire:key="profile-email-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="md:col-span-2">
            <x-button type="submit" size="sm">Salva modifiche</x-button>
        </div>
    </form>
</x-user-dashboard.settings-section>
