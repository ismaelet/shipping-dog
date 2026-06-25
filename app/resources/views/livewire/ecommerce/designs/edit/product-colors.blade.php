<div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

	{{-- Preview --}}
	<x-ecommerce.designs.edit.product-colors.preview
		:image="$this->previewImage"
		:product-id="$productId"
	/>

	<div class="data">
		<div class="item-colors-field colors-pan grid gap-8">
			<x-heading.component class="text-center">Seleziona i colori disponibili</x-heading.component>

			<p class="">Qui puoi selezionare i vari colori disponibili per questo capo e il colore principale,
				che verrà mostrato in tutte le anteprime.</p>

			<div class="grid gap-8">

				{{-- Active Colors --}}
				<x-ecommerce.designs.edit.product-colors.active-colors
					:colors="$this->colors"
					:product-id="$productId"
				/>

				{{-- Default Color --}}
				<x-ecommerce.designs.edit.product-colors.default-color
					:colors="$this->colors"
					:product-id="$productId"
					:active-color-ids="$activeColorIds"
				/>

				{{-- Save button --}}
				<x-ecommerce.designs.edit.product-colors.save-button :save-status="$saveStatus" />
			</div>
		</div>
	</div>
</div>
