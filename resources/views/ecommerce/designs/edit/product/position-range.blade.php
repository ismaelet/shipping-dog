<x-inputs.group>
	<x-inputs.label text="{{ $label }}" />

	<x-inputs.input
		type="range"
		class="h-8 w-full cursor-pointer disabled:cursor-default"
		name="{{ $name }}"
		value="{{ $value }}"
		min="{{ $min }}"
		max="{{ $max }}"
		step="{{ $step }}"
		data-landscape="{{ $orientation ? 'true' : 'false' }}"
		data-position-range
		:disabled="!$enabled"
		x-data
		@input="showSaveButton($event.target)"
	/>

</x-inputs.group>
