@props(['checked' => false, 'disabled' => false, 'label', 'checked'])

<label class="group flex items-center space-x-2">
	<div
		class="focus-visible:ring-ring focus-visible:ring-offset-background group-has-[:checked]:bg-primary bg-surface-500 inline-flex h-7 w-12 shrink-0 cursor-pointer items-center rounded-full border border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 has-[:disabled]:cursor-default has-[:disabled]:opacity-50 has-[:disabled]:saturate-0"
	>
		<input
			type="checkbox"
			role="switch"
			class="hidden"
			@checked($checked)
			@disabled($disabled)
			{{ $attributes->merge() }}
		>
		<span
			data-state="unchecked"
			class="bg-background-400 pointer-events-none block h-5 w-5 translate-x-1 rounded-full shadow-lg ring-0 transition-transform group-has-[:checked]:translate-x-6"
		>
		</span>
	</div>

	<div class="font-medium group-has-[:checked]:hidden">
		{{ $label[0] }}
	</div>

	<div class="font-medium group-has-[input:not(:checked)]:hidden">
		{{ $label[1] }}
	</div>
</label>
