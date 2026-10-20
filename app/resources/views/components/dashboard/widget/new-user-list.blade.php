@php
	use App\Models\User;

	$newUsers = User::orderBy('created_at', 'desc')->take(6)->get();

@endphp

<x-dashboard.widget
	title="Nuovi iscritti"
	subtitle="la lista degli ultimi utenti registrati"
	class="col-span-3"
	variant="big"
>

	<x-slot name="content">
		<div class="mt-4 grid gap-6">

			@if ($newUsers->count())
				@foreach ($newUsers as $user)
					<form
						class="flex items-center text-sm"
						method="POST"
					>
						@csrf

						<span class="aspect-square w-12">
							<img
								src="{{ $user->icon() }}"
								class="bg-surface-500 h-full w-full rounded-full object-cover"
								alt=""
							>
						</span>

						<div class="ml-4 grid gap-1">
							<p class="font-heading text-neutral-heading text-sm font-medium leading-none">
								{{ $user->fullName() ?: $user->email }}
							</p>

							<div class="flex items-center gap-2">
								<p class="text-sm">{{ $user->fullName() ? $user->email : '' }}</p>
							</div>
						</div>

						<div class="ml-auto text-base">
							<span class="text-neutral-heading text-sm">{!! $user->registrationDate() !!}</span>
						</div>
					</form>
				@endforeach
			@else
				<p class="">Non si è registrato ancora nessun utente</p>

			@endif

		</div>
	</x-slot>

</x-dashboard.widget>
