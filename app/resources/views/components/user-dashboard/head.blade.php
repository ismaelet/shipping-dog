@php
	$user = auth()->user();
@endphp

<section class="full-width">
	<div
		class="full-width h-72 bg-cover bg-center"
		style="background-image: url({{ $user->cover() }})"
	></div>

	<div class="grid grid-cols-1 justify-items-center gap-1">

		<div class="border-background -mt-10 h-20 w-20 rounded-full border-[5px]">
			<livewire:user-dashboard.icon />
		</div>

		<livewire:user-dashboard.name />
	</div>
</section>
