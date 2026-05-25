<!--Designs version-->
<div class="list-tbl mt-8">

	<div class="list-tbl-head">
		<p class="list-name shr">Quantità</p>
		<p class="list-name lon">Grafica</p>
		<p class="list-name shr">Guadagno totale</p>
		<p class="list-name shr">Ultima vendita</p>
		<p class="list-name shr">Aggiunto il</p>
	</div>

	@foreach ($items as $item)
		<!--sale line-->
		<div class="list-row">
			<div class="list-cell">
				<p class="list-inp text-sm">{{ $item['total_sales'] }}</p>
			</div>
			<div class="list-cell">
				<p class="list-inp text-sm">{{ $item['name'] }}</p>
			</div>
			<div class="list-cell">
				<p class="list-inp text-sm">{{ $item['total_revenue'] }}</p>
			</div>
			<div class="list-cell">
				<p class="list-inp text-sm">{{ $item['lastOrderCreatedAtString'] }}</p>
			</div>
			<div class="list-cell">
				<p class="list-inp text-sm">{{ $item['createdAtString'] }}</p>
			</div>
		</div>
	@endforeach

</div>
