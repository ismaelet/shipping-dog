@foreach ($salesByMonth as $month => $data)
	<div class="sales month-box mar-b-1 py-8">

		<h3 class="month-title inlb br-c1"><span class="txt-med">{{ $data['month'] }}</span> <span
				class="title-year">{{ $selectedYear }}</span></h3>

		<p class="month-gain inlb"><strong>{{ $data['gain'] }}</strong> da
			<strong>{{ $data['salesCount'] }}</strong>
			vendite
		</p>

		<button
			type="button"
			class="sbtn month-btn clickable"
			onclick="$(this).next().toggleClass('hidden'); $(this).hide();"
		><span class="month-btn-text">Mostra le vendite</span></button>

		<div class="tbl-box pad-t-2 hidden">
			<div class="sale-history__grid grid">

				<div class="grid-row sale-history__head">
					<p class="grid-item">ID Ordine</p>
					<p class="grid-item">Design</p>
					<p class="grid-item">Guadagno</p>
					<p class="grid-item">Capo</p>
					<p class="grid-item">Colore</p>
					<p class="grid-item">Sconto</p>
					<p class="grid-item">Data</p>
				</div>

				@foreach ($data['orderItems'] as $orderItem)
					<!--sale line-->
					<div class="grid-row">
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->idCode() }}</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">
								@if ($orderItem->quantity > 1)
									{{ $orderItem->quantity }} x
								@endif
								{{ $orderItem->product ? $orderItem->product->name : '- design rimosso -' }}
							</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->gainString() }}</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->product->type->name ?? '' }}</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->color->name ?? '' }}</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->order?->code ?: '-' }}</p>
						</div>
						<div class="grid-item">
							<p class="list-inp">{{ $orderItem->createdAtString() }}</p>
						</div>
					</div>
				@endforeach

			</div>
		</div>
	</div>
@endforeach

@if (count($yearsWithSales) > 1)
	<div
		id="years-box"
		class="pad-t-2 pad-b-5 gallery text-center"
	>
		@foreach ($yearsWithSales as $year)
			<label>

				<input
					type="checkbox"
					class="hidden"
					value="{{ $year }}"
					onchange="$('#year-inp').val($(this).val()); $('#search-btn').click();"
				>

				<p
					class="year clickable inlb bgr-c1-h txt-wht-h bg-surface @if ($year == $selectedYear) active @endif rounded">
					{{ $year }}
				</p>

			</label>
		@endforeach

	</div>
@endif
