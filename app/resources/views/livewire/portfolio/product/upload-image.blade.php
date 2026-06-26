<div class="columns-15rem grid gap-8">
	@foreach ($images as $colorName => $image)
		<div class="">
			<h4>{{ $colorName }}</h4>

			<livewire:upload-image
				:key="'upload-' . $positionId . '-' . $colorName"
				:url="$image['url']"
				:quality="$image['quality']"
				:size="$image['size']"
			/>
		</div>
	@endforeach
</div>
