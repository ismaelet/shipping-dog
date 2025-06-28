@use('App\Models\Ecommerce\Product')
@use('App\Models\Ecommerce\Product\ProductType')
@use('App\Models\Ecommerce\Product\ProductGender')

@props(['group', 'genderId' => null, 'seller' => null, 'searchGenderTag' => null, 'searchTypeTag' => null])


@php
	$typeCollection = collect();
	$gender = ProductGender::find($genderId);

	$types = ProductType::where('category', $group)->where('hidden', false)->doesntHave('main_type');

	if ($group == 'c') {
	    $types = $types->where(function ($query) use ($gender) {
	        $query
	            ->whereHas('gender', function ($query) use ($gender) {
	                $query->where('id', $gender->id);
	            })
	            ->orWhere('unisex', true);
	    });
	}

	if ($seller) {
	    $types = $types->whereHas('products', function ($query) use ($seller) {
	        $query->where('user_id', $seller->user_id)->where('status', true);
	    });
	}

	$types = $types->orderBy('sort')->get();

	foreach ($types as $productType) {
	    $checked = $gender && $searchGenderTag == $gender->tag && $searchTypeTag == $productType->tag;

	    $typeCollection->push(
	        (object) [
	            'tag' => $productType->tag,
	            'names' => $productType->names,
	            'checked' => $checked,
	        ],
	    );
	}

	$hasCheckedType = $typeCollection->contains(fn($type) => $type->checked);
@endphp

@pushOnce('scripts')
	<script defer>
		document.addEventListener('DOMContentLoaded', () => {
			const genderCheckboxes = document.querySelectorAll('input[name="gender"]');

			genderCheckboxes.forEach(checkbox => {
				checkbox.addEventListener('change', () => {
					genderCheckboxes.forEach(cb => {
						if (cb !== checkbox) {
							cb.checked = false;
						}
					});

					if (!checkbox.checked) {
						const typeCheckboxes = document.querySelectorAll('.type-cbx');
						typeCheckboxes.forEach(typeCheckbox => {
							typeCheckbox.checked = false;
						});
					}
				});
			});
		})
	</script>
@endpushOnce

<div class="category-selector">
	<label class="gender-title-box">
		<input
			type="checkbox"
			name="gender"
			class="hidden"
			value="{{ $gender?->tag ?: 'm' }}"
			@checked($hasCheckedType || ($gender && $searchGenderTag == $gender->tag))
		>

		<p class="gender-title">
			<span class="gender-ico icon"><x-icon-arrow-simple-down /></span>
			<span class="">{{ $gender?->name ?: 'Accessori' }}</span>
		</p>
	</label>

	<div class="gender-ddown">
		@foreach ($typeCollection as $productType)
			<label class="type-selector">
				<input
					type="checkbox"
					class="type-cbx hidden"
					name="type"
					value="{{ $productType->tag }}"
					@checked($gender && $searchGenderTag == $gender->tag && $searchTypeTag == $productType->tag)
				>
				<p class="type-title">{{ $productType->names }}</p>
			</label>
		@endforeach
	</div>
</div>
