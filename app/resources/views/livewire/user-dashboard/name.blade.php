<div class="text-center">
	<x-heading.component class="break-all text-center">{{ $email }}</x-heading.component>

	@if ($fullName)
		<p class="">
			{{ $fullName }}
		</p>
	@endif
</div>
