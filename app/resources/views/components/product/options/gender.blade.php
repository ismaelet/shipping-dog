@php
    $genders = [];
    $genders[] = $product->type->gender;
    $sibling = $product->type->sibling;

    if ($sibling) {
        $genders[] = $sibling->gender;
    }
@endphp

<div>
    <x-product.options.label>Modello</x-product.options.label>

    <div class="product-options columns-[15rem]">
        @foreach ($genders as $key => $gender)
            @php
                $relatedProduct = null;

                if ($key && $product->design_id) {
                    $relatedProduct = App\Models\Ecommerce\Product::where('design_id', $product->design_id)
                        ->where('type_id', $sibling->id)
                        ->first();
                }
            @endphp

            @if (! $key || $relatedProduct)
                <a class="product-option" @if ($relatedProduct) href="{{ $relatedProduct->url() }}" @endif>
                    <input
                        type="radio"
                        class="cbx"
                        name="gender_id"
                        value="{{ $gender->id }}"
                        {{ !$relatedProduct ? 'checked' : '' }}
                    />

                    {{ $gender->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>
