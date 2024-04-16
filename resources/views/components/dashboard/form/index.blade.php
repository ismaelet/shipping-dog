@props(['title', 'type'])

<x-layouts.admin-dashboard>
    <div class="grid gap-12">
        {{-- Title --}}
        <x-dashboard.form.title :$title />

        <form method="POST" class="grid gap-8">
            {{-- Body --}}
            <div class="grid grid-cols-1">
                {{ $slot }}
            </div>

            {{-- Footer --}}
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
</x-layouts.admin-dashboard>
