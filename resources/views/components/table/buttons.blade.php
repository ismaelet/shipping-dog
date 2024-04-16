@aware(['buttons'])

@props(['record', 'editRoute'])

@php
    $classes = ['w-4', 'transition-colors'];
@endphp

<div class="flex items-center gap-4 flex-wrap">

    @foreach ($buttons as $button)
        @switch($button['type'] ?? 'custom')
            @case('edit')
                {{-- Edit button --}}
                <a href="{{ $editRoute }}" title="Modifica" @class([...$classes, 'hover:text-blue'])>
                    {!! embed_icon('edit') !!}
                </a>
            @break

            @case('delete')
                <button type="submit" name="destroy" title="{{ 'Elimina' }}" @class([...$classes, 'hover:text-error'])
                    value="{{ $record->id }}" x-data
                    @click="window.confirm(['Confermi di voler eliminare questo elemento?']);
					">
                    {!! embed_icon('delete') !!}
                </button>
            @break

            @default
                @if (isset($button['name']))
                    {{-- Custom button --}}
                    <button type="submit" name="login" title="{{ $button['label'] }}" @class([
                        ...$classes,
                        'hover:text-primary' => $button['color'] == 'primary',
                        'hover:text-green' => $button['color'] == 'green',
                        'hover:text-blue' => $button['color'] == 'blue',
                        'hover:text-error' => $button['color'] == 'red',
                    ])
                        value="{{ $record->id }}">
                        {!! embed_icon($button['icon']) !!}
                    </button>
                @else
                    {{-- Custom link --}}
                    <a href="{{ str_replace('{id}', $record->id, $button['url']) }}" title="{{ $button['label'] }}"
                        @class($classes)>
                        {!! embed_icon($button['icon']) !!}
                    </a>
                @endif
        @endswitch
    @endforeach
</div>
