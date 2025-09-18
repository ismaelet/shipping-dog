<x-layouts.form
	:$title
	:$locationTag
	size="lg"
>

	<x-slot name="body">
		<p class="label">Seleziona uno o più articoli da cambiare/restiturire:</p>

		<div class="order-list grid gap-4 sm:grid-cols-[10rem_1fr_1fr]">

			@foreach ($order->order_items as $orderItem)
				<div class="col-span-full grid grid-cols-subgrid items-center rounded dark:bg-neutral-200">
					{{-- Image --}}
					<div class="h-full">
						<img
							src="{{ $orderItem->image() }}"
							class="h-full rounded object-cover"
						>
					</div>

					{{-- Data --}}
					<div class="justify-self-center p-4 pb-0 sm:p-0">
						<h3 class="text-lg">{{ $orderItem->product_with_trashed->name }}</h3>

						<p class="text-small-paragraph">
							{{ $orderItem->description() }}
						</p>
					</div>

					{{-- Action --}}
					<div
						class="p-4 sm:py-4 sm:pr-4"
						x-data="{ showSizeSelector: false }"
					>
						{{-- Action --}}
						<x-label>Azione richiesta:</x-label>
						<x-select
							name="action_{{ $orderItem->id }}"
							class="w-full"
							@change="showSizeSelector = $event.target.value === 'change'"
						>

							<option value>Nessuna</option>

							@if ($orderItem->product_with_trashed->type->sizes->count() > 1)
								<option value="change">Cambio</option>
							@endif

							<option value="refund">Rimborso</option>
						</x-select>

						<div
							class="js-size-selector mt-4"
							x-show="showSizeSelector"
							x-cloak
						>
							<x-label class="">Taglia desiderata:</x-label>
							<x-select
								name="size_{{ $orderItem->id }}"
								class="w-full"
							>
								@foreach ($orderItem->product_with_trashed->type->sizes->where('id', '!=', $orderItem->size_id) as $size)
									<option value="{{ $size->id }}">{{ $size->name }}</option>
								@endforeach
							</x-select>
						</div>
					</div>
				</div>
			@endforeach

		</div>

		@session('error')
			<x-alerts.warning>{{ session('error') }}</x-alerts.warning>
		@endsession

	</x-slot>

	<x-slot name="footer">
		<x-button type="submit">Richiedi</x-button>
		<p>
			<button
				type="submit"
				formaction="{{ route('support.refund.cancel', $changeRequest) }}"
				class="text-error-400 cursor-pointer"
			>Cancella cambio/reso</button>
		</p>
	</x-slot>
</x-layouts.form>
