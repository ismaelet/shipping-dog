<div class="text-center">
	<x-typography.component-heading class="break-all text-center">{{ $email }}</x-typography.component-heading>

	@if ($fullName)
		<p class="">
			{{ $fullName }}
		</p>
	@endif
</div>
