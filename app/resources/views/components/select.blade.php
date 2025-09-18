@props([
    'options' => null,
])

<select
	{{ $attributes->merge(['class' => 'select focus:outline-primary-400 outline-neutral-250 rounded-sm px-[0.75em] py-[0.75em] leading-[2.8] outline transition-colors dark:bg-neutral-200']) }}
>
	@if ($options)
		@foreach ($options as $optionValue => $label)
			<option
				value="{{ $optionValue }}"
				@if ($optionValue == $value) selected @endif
			>
				{{ $label }}
			</option>
		@endforeach
	@else
		{{ $slot }}
	@endif
</select>
