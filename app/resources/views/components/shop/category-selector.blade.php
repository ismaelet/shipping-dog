@props([
    'title',
    'types',
    'genderId' => null,
    'selectedTypeId' => null,
    'selectedGenderId' => null,
    'defaultOpen' => false,
])

@php
	$genderId = $genderId !== null ? (int) $genderId : null;

	$isSelected = $types->contains(function ($type) use ($selectedTypeId, $selectedGenderId, $genderId) {
	    return (int) $selectedTypeId === (int) $type->id &&
	        ($selectedGenderId !== null ? (int) $selectedGenderId : null) === $genderId;
	});
@endphp

<div
	x-data="{ open: @js($isSelected || $defaultOpen) }"
	class="border-b border-white/10 last:border-0"
>
	{{-- Category heading --}}
	<button
		type="button"
		@click="open = !open"
		:aria-expanded="open.toString()"
		@class([
			'flex w-full cursor-pointer items-center justify-between px-4 py-3.5 text-left text-sm font-bold transition-colors',
			'text-primary-300' => $isSelected,
			'text-neutral-heading hover:bg-slate-700' => !$isSelected,
		])
	>
		<span>{{ $title }}</span>

		<x-icon-arrow-simple-down
			class="size-3 shrink-0 -rotate-90 transition-transform duration-300"
			x-bind:class="{ 'rotate-0!': open }"
		/>
	</button>

	{{-- Product types --}}
	<div
		x-show="open"
		x-collapse
		x-cloak
	>
		@foreach ($types as $type)
			@php
				$checked =
				    (int) $selectedTypeId === (int) $type->id &&
				    ($selectedGenderId !== null ? (int) $selectedGenderId : null) === $genderId;
			@endphp

			<button
				type="button"
				wire:key="category-{{ $genderId ?? 'accessories' }}-{{ $type->id }}"
				wire:click="selectType({{ $type->id }}, {{ $genderId === null ? 'null' : $genderId }})"
				@class([
					'flex min-h-11 w-full cursor-pointer items-center px-8 py-2 text-left text-sm font-medium transition-colors',
					'bg-background-400 text-neutral-heading' => $checked,
					'bg-surface text-neutral-text hover:bg-background-400 hover:text-neutral-heading' => !$checked,
				])
				aria-pressed="{{ $checked ? 'true' : 'false' }}"
			>
				{{ $type->names }}
			</button>
		@endforeach
	</div>
</div>
