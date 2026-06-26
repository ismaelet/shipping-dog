<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\BasePrice;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use App\Services\Ecommerce\ProductPriceCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomProductController extends Controller {

    /**
     * Display the form to add a custom product
     */
    public function create(): View {
        $tempId = uniqid('temp_');

        // Fetch types
        $types = ProductType::where('id', '<>', 16)
            ->with('positions')
            ->orderBy('id')
            ->get();

        $imagePositionTypes = $types->first()?->positions;

        // Fetch colors
        $colors = ProductColor::orderBy('reorder')->get();

        // Fetch price references for each type
        $priceReferences = $this->getPriceReferences($types);

        return view('seller-dashboard.portfolio.products.show', [
            'tempId' => $tempId,
            'types' => $types,
            'colors' => $colors,
            'imagePositionTypes' => $imagePositionTypes,
            'priceReferences' => $priceReferences,
        ]);
    }

    /**
     * Display the custom product page
     */
    public function show(Product $product): View {
        // Fetch types
        $types = ProductType::where('id', '<>', 16)->orderBy('sort')->get();

        // Fetch colors
        $colors = ProductColor::orderBy('reorder')->get();

        // Fetch price references for each type
        $priceReferences = $this->getPriceReferences($types, $product);

        return view('seller-dashboard.portfolio.products.show', [
            'product' => $product,
            'types' => $types,
            'colors' => $colors,
            'imagePositionTypes' => $product->type?->all_positions,
            'priceReferences' => $priceReferences,
        ]);
    }

    /**
     * Handle the submission of the custom product form
     */
    public function store(
        Request $request,
        ProductPriceCalculator $calculator,
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type_id' => ['required', 'integer', 'exists:product_types,id'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
            'default-color-id' => ['required', 'integer', 'exists:product_colors,id'],
            'colors' => ['required', 'array', 'min:1'],
            'colors.*' => ['required', 'integer', 'distinct', 'exists:product_colors,id'],
            'cover-image' => ['nullable', 'image', 'max:5120'],
            'more-images' => ['nullable', 'array'],
            'more-images.*' => ['image', 'max:5120'],
            'temp_id' => ['required', 'string', 'regex:/^temp_[a-zA-Z0-9]+$/'],
        ]);

        $type = ProductType::with('positions')->findOrFail($validated['type_id']);

        $defaultColorId = (int) $validated['default-color-id'];
        $activeColorIds = collect($validated['colors'])
            ->map(fn($colorId) => (int) $colorId)
            ->unique()
            ->values();

        // Check if the default color is among the active colors
        if (!$activeColorIds->contains($defaultColorId)) {
            return back()
                ->withErrors([
                    'default-color-id' => 'Il colore predefinito deve essere anche un colore attivo.',
                ])
                ->withInput();
        }

        $position = $type->positions->first();

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'design_id' => false,
            'user_id' => Auth::id(),
            'type_id' => $type->id,
            'color_id' => $defaultColorId,
            'positions' => $position?->id,
            'status' => true,
        ]);

        // Save the active colors
        $product->colors()->sync($activeColorIds->all());


        // Move temporary images to the product's permanent storage
        $this->moveTemporaryProductImages(
            tempId: $validated['temp_id'],
            productId: $product->id,
        );

        // Save the description
        ProductDescription::updateOrCreate(
            ['product_id' => $product->id],
            ['description' => $validated['description'] ?? null]
        );

        // Save the price
        $priceReferences = $this->getPriceReferences(collect([$type]));

        $reference = $priceReferences[$type->id];

        $calculatedPrice = $calculator->calculate(
            newPrice: (float) str_replace(',', '.', $validated['price']),
            referencePrice: $reference['price'],
            referenceEarningsForLightGarments: $reference['gain_w'],
            referenceEarningsForDarkGarments: $reference['gain_c'],
        );

        ProductPrice::updateOrCreate(
            ['product_id' => $product->id],
            [
                'price' => $calculatedPrice['price'],
                'gain_w' => $calculatedPrice['earnings_for_light_garments'],
                'gain_c' => $calculatedPrice['earnings_for_dark_garments'],
            ],
        );

        // Return to the product page with a success message
        return redirect()
            ->route('seller-dashboard.portfolio.products.show', $product)
            ->with('success', 'Il prodotto è stato creato con successo.');
    }

    /**
     * Resource method: update the specified product in storage
     */
    public function update(
        Request $request,
        Product $product,
        ProductPriceCalculator $calculator
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type_id' => ['required', 'integer', 'exists:product_types,id'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);

        $description = $validated['description'] ?? null;

        $newType = null;
        $typeChanged = $product->type_id !== (int) $validated['type_id'];

        if ($typeChanged) {
            $newType = ProductType::with('positions')
                ->findOrFail($validated['type_id']);

            $references = $this->getPriceReferences(
                collect([$newType])
            );

            $reference = $references[$newType->id];
        } else {
            $currentPrice = $product->price();

            $reference = [
                'price' => (float) $currentPrice->price,
                'gain_w' => (float) $currentPrice->gain_w,
                'gain_c' => (float) $currentPrice->gain_c,
            ];
        }

        $calculatedPrice = $calculator->calculate(
            newPrice: (float) str_replace(',', '.', $validated['price']),
            referencePrice: (float) $reference['price'],
            referenceEarningsForLightGarments: (float) $reference['gain_w'],
            referenceEarningsForDarkGarments: (float) $reference['gain_c'],
        );

        $productData = [
            'name' => $validated['name'],
            'type_id' => $validated['type_id'],
        ];

        if ($newType) {
            $productData['positions'] = $newType->positions->first()?->id;
        }

        $product->update($productData);

        ProductDescription::updateOrCreate(
            ['product_id' => $product->id],
            ['description' => $description]
        );

        // Price
        ProductPrice::updateOrCreate(
            ['product_id' => $product->id],
            [
                'price' => $calculatedPrice['price'],
                'gain_w' => $calculatedPrice['earnings_for_light_garments'],
                'gain_c' => $calculatedPrice['earnings_for_dark_garments'],
            ],
        );

        // Return to the product page with a success message
        return redirect()
            ->route('seller-dashboard.portfolio.products.show', $product)
            ->with('success', 'Il prodotto è stato aggiornato con successo.');
    }

    /**
     * Delete a custom product
     */
    public function delete(Product $product): RedirectResponse {
        $product->delete();

        return redirect()->route('seller-dashboard.portfolio')->with('success', 'Prodotto eliminato con successo.');
    }

    /**
     * Helper method: move temporary product images to the product's permanent storage
     */
    private function moveTemporaryProductImages(
        string $tempId,
        int $productId,
    ): void {
        $disk = Storage::disk('public');
        $temporaryDirectory = "products/temp/{$tempId}";
        $destinationDirectory = 'products/custom';

        if (!$disk->exists($temporaryDirectory)) {
            return;
        }

        foreach ($disk->files($temporaryDirectory) as $sourcePath) {
            $filename = basename($sourcePath);
            $destinationPath = "{$destinationDirectory}/{$productId}-{$filename}";

            if ($disk->exists($destinationPath)) {
                $disk->delete($destinationPath);
            }

            $disk->move($sourcePath, $destinationPath);
        }

        $disk->deleteDirectory($temporaryDirectory);
    }


    /**
     * Helper method: get the reference price data used for every selectable product type
     */
    private function getPriceReferences(
        Collection $types,
        ?Product $product = null,
    ): array {
        $seller = Auth::user()->seller;

        return $types->mapWithKeys(function (ProductType $type) use ($seller, $product) {

            if ($product && $product->type_id === $type->id) {
                // When editing an existing product, use its price data
                $price = $product->price();
            } else {
                // When creating a new product, use the seller's price data or the base price as a fallback

                $price = SellerPrice::query()
                    ->where('seller_id', $seller->id)
                    ->where('type_id', $type->id)
                    ->first();

                $price ??= BasePrice::query()
                    ->where('type_id', $type->id)
                    ->first();
            }

            if (!$price) {
                return [
                    $type->id => [
                        'price' => 0,
                        'gain_w' => 0,
                        'gain_c' => 0,
                        'minimum_price' => 0,
                    ],
                ];
            }

            $minimumPrice = round(
                $price->price
                    - max($price->gain_w, $price->gain_c)
                    / ProductPriceCalculator::SELLER_PERCENTAGE,
                2,
            );

            return [
                $type->id => [
                    'price' => round((float) $price->price, 2),
                    'gain_w' => round((float) $price->gain_w, 2),
                    'gain_c' => round((float) $price->gain_c, 2),
                    'minimum_price' => $minimumPrice,
                ],
            ];
        })->all();
    }
}
