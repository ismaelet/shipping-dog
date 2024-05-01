@props(['title' => $type == 'add' ? 'Aggiungi' : 'Modifica', 'viewName', 'type'])

<x-templates.admin-dashboard>

    <div class="grid gap-12">
        <x-dashboard.form-title :$title :$viewName />

        <form method="POST" class="grid gap-8">
            <div class="grid grid-cols-1">
                {{ $slot }}
            </div>

            <div class="flex gap-8">
                @if ($type == 'add')
                    <x-inputs.button name="add">
                        {!! embed_icon('add') !!}
                        Aggiungi
                    </x-inputs.button>
                @else
                    <x-inputs.button name="update">
                        {!! embed_icon('save') !!}
                        Salva
                    </x-inputs.button>
                @endif
            </div>
        </form>
    </div>

</x-templates.admin-dashboard>
