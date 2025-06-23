@props(['product'])

@use('App\Models\Ecommerce\Product')
@use('App\Models\Ecommerce\Product\ProductType')

@php
	$typeTag = $product->type->tag;
	$accessoryType = ProductType::where('tag', $typeTag == 'patch' ? 'hat' : 'patch')
	    ->where('hidden', false)
	    ->first();

	$accessory = Product::where('type_id', $accessoryType->id)->where('design_id', $product->design_id)->first();
@endphp

<div class="mt-8">
	<h3 class="desc_stitle">{{ $typeTag == 'patch' ? 'Cappellino per la toppa' : 'Toppe ricambiabili' }}</h3>
	<p>
		{{ $typeTag == 'patch' ? 'La toppa è da applicare su uno dei nostri cappellini. Se non ne hai già uno, puoi acquistare quello qui sotto, o qualsiasi altro cappellino presente sul nostro shop per usare la toppa' : 'Il cappellino arriverà già con la toppa con il design desiderato, ma puoi acquistare altre toppe con la stessa grafica, ma di un colore diverso, oppure con una grafica diversa, per cambiare il look del tuo cappellino quando vuoi.' }}
	</p>

	<a href="{{ $accessory->url() }}">
		<img
			src="{{ $accessory->image(1024) }}"
			alt="{{ $accessory->name }}"
			class="mt-8 w-full max-w-[200px] rounded-lg shadow-sm"
		/>
	</a>
</div>
