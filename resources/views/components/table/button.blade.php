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
			{!! embed_icon('edit') !!}
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
			@click="window.confirm(['Confermi di voler eliminare questo elemento?']);
					"
		>
			{!! embed_icon('delete') !!}
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
				{!! embed_icon('download') !!}
			</a>
		@endif
	@break

	@default
		<form
			action="{{ route($button['route'], $record) }}"
			method="{{ $button['method'] ?? 'GET' }}"
		>
			@csrf
			<button
				type="submit"
				title="{{ $button['label'] }}"
				@class($classes)
			>
				{!! embed_icon($button['icon']) !!}
			</button>
		</form>
@endswitch
