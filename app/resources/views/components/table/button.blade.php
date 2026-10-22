@aware(['record', 'editRoute'])

@props(['button', 'record', 'editRoute'])

@php
	// Button type
	$button['type'] = $button['type'] ?? 'custom ';
	$classes = ['w-4', 'transition-colors'];

	// Button classes
	if ($button['type'] == 'edit') {
	    $classes[] = 'hover:text-blue';
	} elseif ($button['type'] == 'delete') {
	    $classes[] = 'hover:text-error';
	} else {
	    $classes = array_merge($classes, [
	        'hover:text-blue' => $button['color'] == 'blue',
	        'hover:text-error' => $button['color'] == 'red',
	        'hover:text-primary' => $button['color'] == 'primary',
	        'hover:text-green' => $button['color'] == 'green',
	        'hover:text-dark-green' => $button['color'] == 'dark-green',
	    ]);
	}

	// Methods
	if (isset($button['model_method'])) {
	    $button['value'] = $record->{$button['model_method']}();
	}
@endphp

@switch($button['type'])
	@case('edit')
		{{-- Edit button --}}
		<a
			href="{{ $editRoute }}"
			title="Modifica"
			@class($classes)
		>
			<x-icon-edit />
		</a>
	@break

	@case('delete')
		<button
			type="submit"
			name="destroy"
			title="{{ 'Elimina' }}"
			@class($classes)
			value="{{ $record->id }}"
			x-data
		>
			<x-icon-delete />
		</button>
	@break

	@case('download')
		@php
			$button['url'] = $button['url'] ?? ($button['value'] ?? null);
		@endphp

		@if ($button['url'])
			<a
				href="{{ $button['url'] }}"
				title="{{ $button['label'] ?? 'Download' }}"
				@class($classes)
				download
			>
				<x-icon-download />
			</a>
		@endif
	@break

	@default
		<form
			action="{{ route($button['route'], $record) }}"
			method="{{ isset($button['method']) && $button['method'] == 'GET' ? 'GET' : 'POST' }}"
		>
			@csrf

			@isset($button['method'])
				@method($button['method'])
			@endisset

			<button
				type="submit"
				title="{{ $button['label'] }}"
				name="{{ $button['name'] ?? '' }}"
				value="{{ $button['value'] ?? '' }}"
				@class($classes)
			>

				<x-dynamic-component :component="'icon-' . $button['icon']" />
			</button>
		</form>
@endswitch
