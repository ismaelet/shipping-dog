@pushOnce('scripts')
	<script>
		function togglePosition(toggler) {
			const active = toggler.checked
			const positionPanel = toggler.closest('[data-panel]')
			const rangeInputs = positionPanel.querySelectorAll('[data-position-range]')
			const productPanel = toggler.closest('[data-product-id]')
			const productId = productPanel.dataset.productId;
			const positionSwitches = productPanel.querySelectorAll('[data-position-switch]:checked')
			const positions = Array.from(positionSwitches).map(activeColor => activeColor.value);

			positionPanel.dataset.enabled = active

			rangeInputs.forEach(rangeInput => {
				rangeInput.disabled = !active
			})

			updateProductPositions(productId, positions)

			positionSwitches.forEach(positionSwitch => {
				positionSwitch.disabled = positions.length < 2
			})
		}

		function updateProductPositions(productId, positions) {
			return axios.patch(`/api/products/${productId}`, {
					positions: positions
				})
				.then(response => {
					refreshPreviewImage(productId)
					//console.log(response.data)
				})
				.catch(error => {
					//console.error(error)
				});

		}
	</script>
@endpushOnce

<x-inputs.switch
	:label="['Disattivato', 'Attivo']"
	value="{{ $position->id }}"
	checked="{{ $enabled }}"
	disabled="{{ $enabled && $product->positions()->count() < 2 }}"
	data-position-switch
	x-data
	@input="togglePosition($event.target)"
/>
