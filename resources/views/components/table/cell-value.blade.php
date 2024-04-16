@switch($column['type'] ?? 'default')
    @case('int')
        {{ $record->{$column['name']} ?: '0' }}
    @break

    @case('float')
        {{ $record->{$column['name']} ?: '0.00' }}
    @break

    @case('date')
        {{ $record->{$column['name']} ? $record->{$column['name']}->format('d/m/Y') : '-' }}
    @break

    @case('bool')
        <span class="h-4">
            {!! embed_icon($record->{$column['name']} ? 'correct' : 'wrong') !!}
        </span>
    @break

    @default
        {{ $record->{$column['name']} ?: '-' }}
@endswitch
