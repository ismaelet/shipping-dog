@php
	$user = auth()->user();
@endphp

<section class="breakout">
	<div
		class="relative mx-auto mt-5 h-56 w-full overflow-hidden rounded-3xl bg-cover bg-center sm:h-80"
		style="background-image: url({{ $user->cover() }})"
	>
		<div class="bg-linear-to-r absolute inset-0 from-slate-950/80 via-slate-950/45 to-transparent"></div>
		<p class="absolute bottom-8 left-8 hidden max-w-96 text-sm leading-6 text-white sm:bottom-12 sm:left-16 sm:block">
			Il tuo spazio personale per seguire ordini, collezionare design e far crescere le tue idee.
		</p>
	</div>

	<div class="relative grid grid-cols-1 justify-items-center gap-1">
		<div class="border-background-400 bg-primary-400 -mt-14 h-28 w-28 rounded-full border-[5px]">
			<livewire:user-dashboard.icon />
		</div>

		<p class="text-primary-400 text-sm font-bold uppercase tracking-[0.2em]">Account personale</p>
		<livewire:user-dashboard.name />
	</div>
</section>
