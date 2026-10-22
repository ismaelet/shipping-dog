@props([
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
])

@pushOnce('styles')
	<style>
		.textarea {
			min-height: 6em;
			padding-block: .5em;
		}
	</style>
@endPushOnce

<textarea
 name="{{ $name }}"
 value="{{ $value }}"
 placeholder="{{ $placeholder }}"
 {{ $required ? 'required' : '' }}
 {{ $attributes->class(['textarea input-style']) }}
>{{ $slot }}</textarea>
