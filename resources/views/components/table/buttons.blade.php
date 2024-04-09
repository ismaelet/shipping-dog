@props(['record', 'buttons'])

<div class="flex items-center gap-4 flex-wrap">

    @foreach ($buttons as $button)
        @php
            $classes = [
                'w-4',
                'transition-colors',
                'hover:text-primary' => $button['color'] == 'primary',
                'hover:text-green' => $button['color'] == 'green',
                'hover:text-blue' => $button['color'] == 'blue',
                'hover:text-error' => $button['color'] == 'red',
            ];
        @endphp

        @if (isset($button['name']))
            <button type="submit" name="login" title="{{ $button['label'] }}" @class($classes)
                value="{{ $record->id }}">
                {!! embed_icon($button['icon']) !!}
            </button>
        @else
            <a href="{{ str_replace('{id}', $record->id, $button['url']) }}" title="{{ $button['label'] }}"
                @class($classes)>
                {!! embed_icon($button['icon']) !!}
            </a>
        @endif
    @endforeach
</div>
