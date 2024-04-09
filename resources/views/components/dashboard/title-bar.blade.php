@props(['title', 'addButton' => false])

<section class="flex gap-4 items-center">

    {{-- Title --}}
    <x-dashboard.main-heading>{{ $title }}</x-dashboard.main-heading>

    @if ($addButton)
        {{-- Add button --}}
        <x-dashboard.add-button />
    @endif

</section>
