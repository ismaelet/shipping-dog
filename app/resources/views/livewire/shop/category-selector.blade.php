@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', () => {
			const genderCheckboxes = document.querySelectorAll('input[name="gender"]');
			const typeCheckboxes = document.querySelectorAll('input[name="type"]');

			genderCheckboxes.forEach(checkbox => {
				checkbox.addEventListener('change', () => {
					genderCheckboxes.forEach(cb => {
						if (cb !== checkbox) {
							cb.checked = false;
						}

						if (!checkbox.checked) {
							const categorySelector = checkbox.closest('.js-category-selector');
							const selectedType = categorySelector.querySelector(
								'input[name="type"]:checked');

							if (selectedType) {
								selectedType.click()
							}
						}
					});
				});
			});

			typeCheckboxes.forEach(checkbox => {
				checkbox.addEventListener('change', () => {
					typeCheckboxes.forEach(cb => {
						if (cb !== checkbox) {
							cb.checked = false;
						}
					});
				});
			});
		});
	</script>
@endpushOnce

<div class="category-selector js-category-selector">
	<label class="gender-title-box">
		<input
			type="checkbox"
			name="gender"
			class="hidden"
			@checked($genderChecked)
		>

		<p class="gender-title">
			<span class="gender-ico icon"><x-icon-arrow-simple-down /></span>
			<span class="">{{ $gender?->name ?: 'Accessori' }}</span>
		</p>
	</label>

	<div class="gender-ddown">
		@foreach ($types as $type)
			<label class="type-selector">
				<input
					type="checkbox"
					class="hidden"
					name="type"
					value="{{ $type->id }}"
					@checked($type->checked)
					@click="$dispatch('selectType', { typeId: '{{ $type->id }}', genderId: {{ $gender ? "'" . $gender->id . "'" : 'null' }} })"
				>
				<p class="type-title">{{ $type->names }}</p>
			</label>
		@endforeach
	</div>
</div>
