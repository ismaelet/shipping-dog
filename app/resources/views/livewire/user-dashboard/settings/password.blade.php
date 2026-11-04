<x-user-dashboard.settings-section
    title="Modifica la password"
    text="Scegli una password unica e difficile da indovinare."
    icon="lock"
    tone="detail-1"
>
    <form class="grid gap-5 md:grid-cols-2" wire:submit="save">
        @session('success')
            <x-alert wire:key="password-success" type="success" class="md:col-span-2">{{ session('success') }}</x-alert>
        @endsession

        <div class="grid content-start gap-2 md:col-span-2" wire:key="password-current_password">
            <x-label>Password attuale</x-label>
            <x-input
                class="w-full"
                type="password"
                wire:model="current_password"
                placeholder="Inserisci la password attuale"
                required
            />

            @error('current_password')
                <x-alert wire:key="password-current_password-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2" wire:key="password-new_password">
            <x-label>Nuova password</x-label>
            <x-input
                class="w-full"
                type="password"
                wire:model="new_password"
                placeholder="Almeno 8 caratteri"
                required
            />

            @error('new_password')
                <x-alert wire:key="password-new_password-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="grid content-start gap-2" wire:key="password-new_password_confirmation">
            <x-label>Conferma password</x-label>
            <x-input
                class="w-full"
                type="password"
                wire:model="new_password_confirmation"
                placeholder="Ripeti la nuova password"
                required
            />

            @error('new_password_confirmation')
                <x-alert wire:key="password-new_password_confirmation-error" type="error">{{ $message }}</x-alert>
            @enderror
        </div>

        <div class="md:col-span-2">
            <x-button type="submit" size="sm">Aggiorna password</x-button>
        </div>
    </form>
</x-user-dashboard.settings-section>
