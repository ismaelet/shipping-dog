<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<title>Storico ordini</title>
	<style>
		body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172b; }
		h1 { font-size: 20px; margin: 0 0 4px; }
		.meta { color: #62748e; margin-bottom: 20px; }
		table { width: 100%; border-collapse: collapse; }
		th { text-align: left; font-size: 9px; text-transform: uppercase; color: #62748e; border-bottom: 1px solid #cad5e2; padding: 6px 4px; }
		td { padding: 8px 4px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
		.sub { color: #62748e; font-size: 9px; }
		.right { text-align: right; }
	</style>
</head>
<body>
	<h1>Storico ordini</h1>
	<p class="meta">{{ $user->name }} · {{ $user->email }} · Generato il {{ $generatedAt }}</p>

	<table>
		<thead>
			<tr>
				<th>Ordine</th>
				<th>Articoli</th>
				<th>Stato</th>
				<th>Data</th>
				<th class="right">Totale</th>
			</tr>
		</thead>
		<tbody>
			@forelse ($orders as $order)
				<tr>
					<td>{{ $order->idCode() }}</td>
					<td>
						{{ $order->mainTitle() }}<br>
						<span class="sub">{{ $order->subTitle() }}</span>
					</td>
					<td>{{ $order->statusName() }}</td>
					<td>{{ $order->humanDate() }}</td>
					<td class="right">{{ \App\Helpers\EcommerceHelper::formatPrice($order->price, true) }}</td>
				</tr>
			@empty
				<tr><td colspan="5">Nessun ordine effettuato</td></tr>
			@endforelse
		</tbody>
	</table>
</body>
</html>
