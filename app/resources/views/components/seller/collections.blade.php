@foreach ($collections as $collection)
	<div @class(['coll-ele', 'al-top', 'coll-box', 'sort-box'])>
		<h3 class="coll-name txt-low text-center">
			{{ $collection->name }}
		</h3>

		<a
			class="collection relative"
			href="{{ $collection->url() }}"
		>
			@if ($collection->image())
				<img
					class="coll-img h-full w-full object-cover"
					src="{{ $collection->image() }}"
					alt="{{ $collection->name }}"
				/>
			@endif

			<div class="coll-cover bgr"></div>
		</a>
	</div>
@endforeach
