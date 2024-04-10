@props(['fields', 'orderBy', 'orderDirection', 'records', 'buttons', 'interactiveRows'])

<section class="p-8 surface">
    <table class="w-full">
        {{--  Table head  --}}
        <x-table.head :$fields :$orderBy :$orderDirection />

        {{-- Table body --}}
        <x-table.body :$fields :$records :$buttons :$interactiveRows />
    </table>
</section>
