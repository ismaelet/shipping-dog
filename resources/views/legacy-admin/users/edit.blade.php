<x-dashboard.form :$title :$type>

    <x-slot name="body">
        <x-dashboard.form.panel title="Dati personali">

            <x-form.group>
                <x-inputs.label for="first_name">Nome</x-inputs.label>
                <x-inputs.input name="first_name" placeholder="Nome" value="{{ $record ? $record->first_name : '' }}" />
            </x-form.group>

            <x-form.group>
                <x-inputs.label for="last_name">Cognome</x-inputs.label>
                <x-inputs.input name="last_name" placeholder="Cognome" value="{{ $record ? $record->last_name : '' }}" />
            </x-form.group>

            <x-form.group>
                <x-inputs.label for="email">E-mail</x-inputs.label>
                <x-inputs.input name="email" placeholder="E-mail" value="{{ $record ? $record->email : '' }}" />

                @error('email')
                    <x-inputs.warning>{{ $message }}</x-inputs.warning>
                @enderror
            </x-form.group>

        </x-dashboard.form.panel>
    </x-slot>

    <x-slot name="footer">
        <x-dashboard.form.save-button />
    </x-slot>

</x-dashboard.form>
