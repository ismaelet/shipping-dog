@props (['title', 'description' => null])

<section {{ $attributes->class('card grid min-w-0 gap-6 p-4 sm:p-6') }}>
  <header class="border-border-400 grid gap-2 border-b pb-4">
    <x-heading variant="article" level="2">{{ $title }}</x-heading>
    @if ($description)
      <p class="text-text-400 text-sm leading-6">{{ $description }}</p>
    @endif
  </header>
  {{ $slot }}
</section>
