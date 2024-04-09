@props(['field', 'orderBy', 'orderDirection'])

@php
    $hasName = isset($field['name']);
    $active = $hasName && $orderBy == $field['name'];
    $orderDirection = $active && $orderDirection == 'desc' ? 'asc' : 'desc';
@endphp

@pushOnce('scripts')
    <script defer>
        function sort_table(field) {
            field.querySelectorAll('input').forEach((element) => {
                element.checked = true
            })

            field.closest('form').submit()
        }
    </script>
@endpushOnce

<th x-data @click="sort_table($event.target)"
    class="h-12 px-4 text-left align-middle cursor-pointer font-medium text-neutral-300 hover:text-primary transition-colors">
    @if ($hasName)
        <input type="radio" name="order_by" value="{{ $field['name'] }}" class="hidden">
        <input type="radio" name="order_direction" value="{{ $orderDirection }}" class="hidden">

        {{ $field['label'] }}

        @if ($active)
            {!! $orderDirection == 'asc' ? '&#x25BE;' : '&#x25B4;' !!}
        @endif
    @endif
</th>
