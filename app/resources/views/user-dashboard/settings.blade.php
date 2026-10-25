<x-user-dashboard :$title>
    <section class="border-border-400 bg-surface-400 rounded-3xl border p-5 sm:p-8">
        <x-user-dashboard.panel-header
            title="Il tuo profilo"
            subtitle="Impostazioni"
            text="Aggiorna i dati personali, l'indirizzo e le preferenze dell'account."
        />

        <div class="mt-8 grid gap-8">
            <livewire:user-dashboard.settings.profile />

            <livewire:user-dashboard.settings.password />

            <livewire:user-dashboard.settings.upload-icon />

            {{-- Delete account --}}
            <form
                class="border-danger-400/20 bg-danger-400/10 rounded-2xl border p-6"
                method="POST"
                action="{{ route('user.destroy') }}"
            >
                @csrf
                @method('DELETE')

                <h3 class="font-heading text-danger-300 text-xl font-semibold">Elimina l'account</h3>
                <p class="text-danger-200/60 mt-2 text-sm leading-6">
                    Questa operazione elimina definitivamente dati, ordini salvati e preferenze. Non può essere
                    annullata.
                </p>

                <x-button
                    class="mt-6"
                    type="submit"
                    size="small"
                    color="danger"
                    variant="secondary"
                    confirmation="Sei sicuro di voler eliminare il profilo? Non potrà essere ripristinato."
                >
                    Elimina l'account
                    <x-icon-delete />
                </x-button>
            </form>
        </div>
    </section>
</x-user-dashboard>
