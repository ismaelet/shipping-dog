<x-templates.admin-form :$title :$viewName :$type>

    <x-dashboard.form title="Dati personali">

        <x-form.field>
            <x-form.label for="username">Username</x-form.label>
            <x-form.input name="username" placeholder="Username" value="{{ $record ? $record->username : '' }}" />
        </x-form.field>

        <x-form.field>
            <x-form.label for="first_name">Nome</x-form.label>
            <x-form.input name="first_name" placeholder="Nome" value="{{ $record ? $record->first_name : '' }}" />
        </x-form.field>

        <x-form.field>
            <x-form.label for="last_name">Cognome</x-form.label>
            <x-form.input name="last_name" placeholder="Cognome" value="{{ $record ? $record->last_name : '' }}" />
        </x-form.field>

        <x-form.field>
            <x-form.label for="email">E-mail</x-form.label>
            <x-form.input name="email" placeholder="E-mail" value="{{ $record ? $record->email : '' }}" />
        </x-form.field>

    </x-dashboard.form>

</x-templates.admin-form>
