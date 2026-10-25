@php
	use App\Models\Ecommerce\Seller;

	$awaitingSellers = Seller::awaitingApproval();
@endphp

<x-dashboard.widget
	title="Negozi da Valutare"
	subtitle="la lista dei negozi in attesa di valutazione"
	class="col-span-3"
	variant="big"
>

	<x-slot name="content">
		<div class="mt-4 grid gap-6">
			@if ($awaitingSellers->count())
				@foreach ($awaitingSellers as $seller)
					<form
						class="flex items-center text-sm"
						method="POST"
					>
						@csrf

						<span class="aspect-square w-12">
							<img
								src="{{ $seller->icon() }}"
								class="bg-surface-500 h-full w-full rounded-full object-cover"
								alt=""
							>
						</span>

						<div class="ml-4 grid gap-1">
							<p class="font-heading text-heading-400 text-base font-medium leading-none">
								{{ $seller->name }}</p>

							<div class="flex items-center gap-2">
								<button
									type="submit"
									name="accept"
									class="action-button text-success"
									value="{{ $seller->id }}"
								>accetta</button>
								<button
									type="submit"
									name="deny"
									class="action-button text-error"
									value="{{ $seller->id }}"
								>rifiuta</button>
								<a
									href="{{ $seller->url() }}"
									class="action-button text-blue"
								>visualizza</a>
							</div>
						</div>

						<div class="ml-auto text-base">
							<span class="text-heading-400 font-medium">{{ $seller->designs->count() }}</span> design
						</div>
					</form>
				@endforeach
			@else
				<p class="">Non c'è nessun negozio da valutare</p>
			@endif
		</div>
	</x-slot>

</x-dashboard.widget>
