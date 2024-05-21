@use ('App\Helpers\EcommerceHelper');

<x-layouts.email :$title>
	<table width="100%">

		<tr>
			<td>
				<h1 style="font-size: 32px; font-weight: 600; color: hsl(0, 0%, 13%)">{{ $title }}</h1>
				<h3 style="padding-bottom: 16px">Ordine n&deg; {{ $order->idCode() }}</h3>
			</td>
		</tr>

		<tr>
			<td style="padding: 32px; background-color: #f4f4f4; text-align: center">
				<p>{!! $info !!}</p>
			</td>
		</tr>

		<tr>
			<td style="padding-top: 32px">
				<center>
					<a href="{{ $order->recapUrlWithToken() }}">
						<p
							style="
							display: inline-block;
							padding: 0 32px;
							background-color: #ff9600;
							border-radius: 32px;
							color: #fff;
							font-size: 18px;
							font-weight: 500;
							line-height: 2.6;
							text-decoration: none;
						"
						>
							Visualizza l'ordine
						</p>
					</a>
				</center>
			</td>
		</tr>

		@if (in_array($order->status, [EcommerceHelper::statusFromTag('received'), EcommerceHelper::statusFromTag('confirmed')]))
			<tr>
				<td style="padding-top: 32px">
					<h2 style="padding-top: 16px; border-top: 1px solid #ebebeb; line-height: 2">Riepilogo dell'ordine</h2>
					<table
						width="100%"
						style="border: 1px solid #ebebeb; padding: 2rem"
					>
						<tr>
							<td
								width="50%"
								style="padding-right: 32px; vertical-align: top"
							>
								<p style="font-weight: 600; margin-bottom: 8px">Informazioni di contatto</p>
								<p style="margin-bottom: 16px">{!! $order->address->email . '<br>' . $order->address->phone !!}</p>
							</td>
							<td style="padding-left: 32px; vertical-align: top">
								<p style="font-weight: 600; margin-bottom: 8px">Forma di pagamento</p>
								<p style="margin-bottom: 16px">{{ $order->payment_method->name ?? '-' }}</p>
							</td>
						</tr>

						<tr>
							<td
								width="50%"
								style="padding-right: 32px; vertical-align: top"
							>
								<p style="font-weight: 600; margin-bottom: 8px">Indirizzo di spedizione</p>
								<p>{!! $order->address->fullAddress() !!}</p>
							</td>
							<td style="padding-left: 32px; vertical-align: top">
								<p style="font-weight: 600; margin-bottom: 8px">Tipo di spedizione</p>
								<p>{!! $order->shipping_method->name !!}</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<h2 style="padding-top: 32px; border-bottom: 1px solid #ebebeb; line-height: 2">Prodotti</h2>
					<table
						width="100%"
						style="border-collapse: collapse"
					>

						@foreach ($order->order_items as $orderItem)
							<tr style="border-bottom: 1px solid #f4f4f4">
								<td>
									<p style="line-height: 3">{{ $orderItem->name() }}</p>
								</td>
								<td>
									<p style="text-align: right">{{ $orderItem->priceString() }}</p>
								</td>
							</tr>
						@endforeach

						<tr>
							<td>
								<p style="">Spedizione:</p>
							</td>
							<td>
								<p style="text-align: right">{{ $order->shippingFeeString() }}</p>
							</td>
						</tr>
						<tr>
							<td>
								<p><strong>Totale dell'ordine:</strong></p>
							</td>
							<td>
								<p style="text-align: right"><strong>{{ $order->priceString() }}</strong></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		@endif
	</table>
</x-layouts.email>
