@pushOnce('scripts')
	<script>
		function update_design(event) {
			const input = event.target
			const designId = input.dataset.designId
			const name = input.value
			const confirmationIcon = document.querySelector('.js-change-name-confirmation')

			if (name) {
				axios
					.patch(`/api/designs/${designId}`, {
						name: name
					})
					.then((response) => {
						confirmationIcon.classList.remove('opacity-0')

						setTimeout(function() {
							confirmationIcon.classList.add('opacity-0')
						}, 1000)
					})
					.catch((error) => {
						console.error(error)
					})
			}
		}
	</script>
@endpushOnce

<form
	method="POST"
	x-data
>
	@csrf

	<div class="stack-content center-content grid justify-items-end">
		<input
			type="text"
			class="change-name focus:border-primary text-heading-400 w-full border px-8 py-2 text-center text-4xl font-bold tracking-wider transition-colors"
			name="name"
			maxlength="25"
			data-design-id="<?= $design->id ?>"
			value="<?= $design->name ?>"
			@input="update_design($event)"
		>

		<span class="js-change-name-confirmation text-primary pointer-events-none w-6 opacity-0 transition-opacity">
			<x-icon-correct />
		</span>
	</div>
</form>
