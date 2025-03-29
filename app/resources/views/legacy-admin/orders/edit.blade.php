@use('App\Helpers\EcommerceHelper')

@pushOnce('styles')
	<style>
		.status-icon {
			--icon-size: 50%;
			border-color: currentColor;
			color: var(--_status-color);
		}

		.order-list {
			grid-template-columns: max-content max-content 1fr max-content;
		}

		.order-row {
			display: grid;
			grid-template-columns: subgrid;
			grid-column: 1 / -1;
			align-items: center;
			padding-top: .5rem;

			&.order {
				padding-block: 1rem;
				border-top-width: var(--border-width);
			}

			&:nth-last-child(2) {
				padding-bottom: 1rem;
				border-bottom-width: var(--border-width);

			}

			.order+&:not(.order) {
				padding-top: 1rem;
				border-top-width: var(--border-width);
			}

			> :first-child {
				margin-left: 1rem;
			}
		}
	</style>
@endpushOnce

<x-dashboard.form
	:$title
	:$type
>

	<x-slot name="body">
		<div class="grid gap-12 lg:grid-cols-7">

			{{-- Main content --}}
			<div class="lg:col-span-4">
				{{-- Order recap --}}
				<x-dashboard.form.panel title="Dati dell'ordine">
					<div class="flex items-center gap-4">
						<div
							class="status-icon aspect-square h-12 rounded-full border"
							style="--_status-color: {{ $record->statusColor() }}"
						>
							{!! embed_icon($record->statusIcon()) !!}
						</div>

						<div>
							<h3 class="">{{ $record->statusName() }}</h3>
							<p class="text-sm">Ordinato il {{ $record->createdAtString() }}</p>
						</div>
					</div>

					{{-- order_items --}}
					<div class="order-list mt-4 grid gap-x-4">
						<div class="order-row">
							<p class="font-medium text-neutral-300">Prodotto</p>
							<p class="font-medium text-neutral-300">ID</p>
							<p class="font-medium text-neutral-300">Dettagli</p>
							<p class="font-medium text-neutral-300">Prezzo</p>
						</div>

						<!-- order_items list -->

						@foreach ($record->order_items as $orderItem)
							<div class="order-row order">

								<img
									src="{{ $orderItem->image() }}"
									alt=""
									class="bg-surface-400 aspect-square w-24 rounded"
								>
								<p class="">{{ $orderItem->idCode() }}</p>

								<p class="">
									<a href="{{ $orderItem->product_with_trashed->fullUrl($orderItem->color_id, $orderItem->position_id) }}">
										{{ $orderItem->full_name }}
									</a>
								</p>

								<p class="">{{ $orderItem->priceString() }}</p>
							</div>
						@endforeach

						@if ($record->coupon)
							<div class="order-row">
								<p class="">Codice</p>
								<span></span>
								<p class="">{{ $record->code }}</p>
							</div>
						@endif

						@if ($record->discount())
							<div class="order-row">
								<p class="">Sconto</p>
								<span></span>
								<p class="">-{{ $record->discountString() }}</p>
							</div>
						@endif

						<div class="order-row">
							<p class="">Spedizione</p>
							<span></span>
							<p class="">{{ $record->shippingFeeString() }}</p>
						</div>

						<div class="order-row text-lg text-neutral-700">
							<p class="">Totale</p>
							<span></span>
							<p class="">{{ $record->priceString() }}</p>
						</div>
					</div>

				</x-dashboard.form.panel>
			</div>

			{{-- Sidebar --}}
			<div class="grid items-start gap-12 lg:col-span-3">

				@if ($record->address)
					{{-- Customer data --}}
					<x-dashboard.form.panel title="Dati del cliente">
						<x-form.group>
							<h3>Nome completo</h3>
							<p>{{ $record->address->full_name }}</p>
						</x-form.group>

						<x-form.group>
							<h3>Contatti</h3>
							<p>Tel.:
								<a
									class="hover:text-primary text-neutral-700 transition-colors"
									href="tel:{{ $record->address->phone }}"
								>
									{{ $record->address->phone }}</a>
							</p>
							<p>E-mail:
								<a
									class="hover:text-primary text-neutral-700 transition-colors"
									href="mailto:{{ $record->address->email }}"
								>{{ $record->address->email }}</a>
							</p>
						</x-form.group>
					</x-dashboard.form.panel>

					{{-- Delivery data --}}
					<x-dashboard.form.panel title="Destinatario">
						<p class="">{!! implode('<br>', $record->address->full_address) !!}</p>

						@if ($record->status > 0)
							<div class="flex gap-2">
								<x-inputs.input
									name="tracking"
									value="{{ $record->tracking }}"
									placeholder="Codice di tracking"
									class="grow"
								/>

								<span class="hidden">
									<x-inputs.button
										type="submit"
										size="auto"
										name="update_tracking"
									>
										{!! embed_icon('save') !!}
										Salva
									</x-inputs.button>
								</span>
							</div>
						@endif

						{{-- <div>
                        <a href="edit-address?id={{ $record->address->id }}"
                            class="btn button--small btn--secondary button--auto-width">Modifica</a>
                    </div> --}}
					</x-dashboard.form.panel>
				@endif

				{{-- Payment data --}}
				<x-dashboard.form.panel title="Pagamento">
					<h3 class="">{!! $record->payment_method->name !!}</h3>

					<p>ID trasferenza: <span class="font-bold">{{ $record->token ?: 'nessuno' }}</span></p>

					@if ($record->status == EcommerceHelper::statusFromTag('received'))
						<h3 class="">Link del pagamento</h3>
						<p
							class="flex justify-between gap-4"
							x-data
							@click="navigator.clipboard.writeText('{{ $record->recapUrlWithToken() }}'); $dispatch('flash', {type: 'success', message: 'Link copiato negli appunti'});"
						>
							{{ $record->recapUrlWithToken() }}
							<span class="hover:text-primary w-4 cursor-pointer transition-colors">
								{!! embed_icon('copy') !!}
							</span>
						</p>
					@endif
				</x-dashboard.form.panel>
			</div>
		</div>
	</x-slot>

	{{-- Footer --}}
	<x-slot name="footer">

		<div class="flex w-full flex-wrap justify-between gap-4">
			<div class="flex flex-wrap gap-4">
				@if ($record->status != EcommerceHelper::statusFromTag('cancelled'))
					<x-inputs.button
						type="submit"
						variant="secondary"
						color="error"
						name="new_status"
						value="{{ EcommerceHelper::statusFromTag('cancelled') }}"
					>
						{!! embed_icon('delete') !!}
						Cancella l'ordine
					</x-inputs.button>
				@endif

				@if (!in_array($record->status, [EcommerceHelper::statusFromTag('open'), EcommerceHelper::statusFromTag('received')]))
					<x-inputs.button
						type="submit"
						variant="secondary"
						name="new_status"
						value="{{ EcommerceHelper::statusFromTag('received') }}"
					>
						{!! embed_icon('back') !!}
						Imposta come ricevuto
					</x-inputs.button>
				@endif

				@if ($record->status >= EcommerceHelper::statusFromTag('awaiting-shipping'))
					<x-inputs.button
						type="submit"
						variant="secondary"
						name="new_status"
						value="{{ EcommerceHelper::statusFromTag('confirmed') }}"
					>
						{!! embed_icon('back') !!}
						Da trasmettere
					</x-inputs.button>
				@endif

				@if (!$record->user_id && $record->status == EcommerceHelper::statusFromTag('open'))
					<x-inputs.button
						type="submit"
						variant="secondary"
						name="attach_to_current_user"
						value="{{ EcommerceHelper::statusFromTag('confirmed') }}"
					>
						{!! embed_icon('link') !!}
						Associa a me
					</x-inputs.button>
				@endif

				@if ($record->user_id == auth()->id() && $record->status == EcommerceHelper::statusFromTag('open'))
					<x-inputs.button
						type="submit"
						variant="secondary"
						name="detach_from_current_user"
						value="{{ EcommerceHelper::statusFromTag('confirmed') }}"
					>
						{!! embed_icon('link_off') !!}
						Dissocia da me
					</x-inputs.button>
				@endif
			</div>

			@if ($record->status == EcommerceHelper::statusFromTag('cancelled'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('received') }}"
				>
					{!! embed_icon('correct') !!}
					Ripristina l'ordine
				</x-inputs.button>
			@elseif ($record->status == EcommerceHelper::statusFromTag('open'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('received') }}"
				>
					{!! embed_icon('correct') !!}
					Conferma l'ordine
				</x-inputs.button>
			@elseif ($record->status == EcommerceHelper::statusFromTag('received'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('confirmed') }}"
				>
					{!! embed_icon('money') !!}
					Conferma il pagamento
				</x-inputs.button>
			@elseif ($record->status == EcommerceHelper::statusFromTag('confirmed'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('awaiting-shipping') }}"
				>
					{!! embed_icon('correct') !!}
					Etichetta trasmessa
				</x-inputs.button>
			@elseif ($record->status == EcommerceHelper::statusFromTag('awaiting-shipping'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('shipped') }}"
				>
					{!! embed_icon('correct') !!}
					Spedito
				</x-inputs.button>
			@elseif ($record->status == EcommerceHelper::statusFromTag('shipped'))
				<x-inputs.button
					type="submit"
					name="new_status"
					value="{{ EcommerceHelper::statusFromTag('delivered') }}"
				>
					{!! embed_icon('correct') !!}
					Consegnato
				</x-inputs.button>
			@endif
		</div>

	</x-slot>
</x-dashboard.form>
