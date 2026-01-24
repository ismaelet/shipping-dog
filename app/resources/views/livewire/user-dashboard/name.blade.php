@php
	$user = auth()->user();
	$email = $user->email;
	$username = $user->name;
	$fullName = $user->fullName();
	$name = $username ?: $fullName ?: null;
@endphp

<div class="text-center">
	@if ($name)
		<x-heading.component class="break-all text-center">{{ $name }}</x-heading.component>
	@endif

	@if ($username || $fullName)
		<p class="">
			{{ $email }}
		</p>
	@endif
</div>
