@php
	$user = auth()->user();
	$email = $user->email;
	$username = $user->name;
	$fullName = $user->fullName();
	$name = $username ?: $fullName ?: null;
@endphp

<div class="text-center">
	@if ($name)
		<x-heading.component class="break-all text-center text-lg">Ciao, {{ $name }}</x-heading.component>
	@endif

	@if ($username || $fullName)
		<p class="text-neutral-text text-sm">
			{{ $email }}
		</p>
	@endif
</div>
