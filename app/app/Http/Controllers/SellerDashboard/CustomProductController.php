<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Actions\Portfolio\CustomProductAccess;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\BasePrice;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Seller\SellerPrice;
use App\Services\Ecommerce\ProductPriceCalculator;
use App\Services\Files\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomProductController extends Controller
{
    public function create(): View
    {
        $tempId = old('temp_id');
        if (! is_string($tempId) || session('custom-product-uploads.'.$tempId) !== Auth::id()) {
            $tempId = 'temp_'.Str::random(40);
            session(['custom-product-uploads.'.$tempId => Auth::id()]);
        }

        return $this->editor(null, $tempId);
    }

    public function show(Product $product): View
    {
        CustomProductAccess::authorize($product);

        return $this->editor($product);
    }

    private function editor(?Product $product = null, ?string $tempId = null): View
    {
        $product?->loadMissing('type', 'colors', 'descriptionModel', 'specific_price', 'base_price');
        $types = ProductType::where(function ($query) use ($product) {
            $query->where('hidden', false);
            if ($product) {
                $query->orWhereKey($product->type_id);
            }
        })->with('colors', 'positions', 'all_positions')->orderBy('category')->orderBy('sort')->orderBy('id')->get();
        $priceReferences = $this->getPriceReferences($types, $product);
        $initialTypeId = (int) old('type_id', $product?->type_id ?? $types->first()?->id);
        if (! $types->contains('id', $initialTypeId)) {
            $initialTypeId = (int) ($product?->type_id ?? $types->first()?->id);
        }
        $initialColorIds = array_map('intval', (array) old('colors', $product?->colors->modelKeys() ?? []));
        $initialDefaultColorId = (int) old('default-color-id', $product?->color_id ?? 0);
        $initialPrice = old('price', $product ? $priceReferences[$product->type_id]['price'] : null);
        $typeOptions = $types->mapWithKeys(fn ($type) => [$type->id => [
            'colors' => $type->colors->sortBy('reorder')->values()->map(fn ($color) => [
                'id' => $color->id, 'name' => $color->name,
                'primary' => $color->primary_color_hex,
                'secondary' => $color->secondary_color_hex ?: $color->primary_color_hex,
            ])->all(),
        ]])->all();
        $coverPath = $product ? 'products/'.$product->id.'-cover.webp' : null;
        $coverImage = $coverPath && Storage::disk('public')->exists($coverPath) ? storage_asset($coverPath) : null;
        $extraImages = $product ? collect(Storage::disk('public')->files('products/custom'))
            ->filter(fn ($path) => str_starts_with(basename($path), $product->id.'-more-'))
            ->map(fn ($path) => storage_asset($path))->values()->all() : [];

        return view('seller-dashboard.portfolio.products.show', compact('product', 'tempId', 'types', 'priceReferences', 'initialTypeId', 'initialColorIds', 'initialDefaultColorId', 'initialPrice', 'typeOptions', 'coverImage', 'extraImages'));
    }

    public function store(Request $request, ProductPriceCalculator $calculator, ImageProcessor $images): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        CustomProductAccess::authorizeTemporary($validated['temp_id']);
        $type = ProductType::with('positions')->findOrFail($validated['type_id']);
        $reference = $this->getPriceReferences(collect([$type]))[$type->id];

        $product = DB::transaction(function () use ($validated, $type, $reference, $calculator, $request, $images) {
            $product = Product::create([
                'name' => $validated['name'], 'design_id' => 0, 'user_id' => Auth::id(),
                'type_id' => $type->id, 'color_id' => $validated['default-color-id'],
                'positions' => (string) $type->positions->first()->id, 'status' => true,
            ]);
            $this->saveDetails($product, $validated, $reference, $calculator);
            $this->saveImages($request, $product, $images);
            $this->moveTemporaryProductImages($validated['temp_id'], $product->id);

            return $product;
        });
        session()->forget('custom-product-uploads.'.$validated['temp_id']);

        return to_route('seller-dashboard.portfolio.products.show', $product)->with('success', 'Il prodotto è stato creato con successo.');
    }

    public function update(Request $request, Product $product, ProductPriceCalculator $calculator, ImageProcessor $images): RedirectResponse
    {
        CustomProductAccess::authorize($product);
        $validated = $this->validateProduct($request, $product);
        $type = ProductType::with('positions')->findOrFail($validated['type_id']);
        $reference = $this->getPriceReferences(collect([$type]), $product)[$type->id];
        DB::transaction(function () use ($product, $validated, $type, $reference, $calculator, $request, $images) {
            $data = ['name' => $validated['name'], 'type_id' => $type->id, 'color_id' => $validated['default-color-id']];
            if ((int) $product->type_id !== (int) $type->id) {
                $data['positions'] = (string) $type->positions->first()->id;
            }
            $product->update($data);
            $this->saveDetails($product, $validated, $reference, $calculator);
            $this->saveImages($request, $product, $images);
        });

        return to_route('seller-dashboard.portfolio.products.show', $product)->with('success', 'Il prodotto è stato aggiornato con successo.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type_id' => ['required', 'integer', Rule::exists('product_types', 'id')->where(function ($query) use ($product) {
                $query->where(function ($query) use ($product) {
                    $query->where('hidden', false);
                    if ($product) {
                        $query->orWhere('id', $product->type_id);
                    }
                });
            })],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
            'default-color-id' => ['required', 'integer'],
            'colors' => ['required', 'array', 'min:1'],
            'colors.*' => ['required', 'integer', 'distinct'],
            'cover-image' => ['nullable', 'image', 'max:5120'],
            'more-images' => ['nullable', 'array', 'max:10'],
            'more-images.*' => ['image', 'max:5120'],
            'temp_id' => [$product ? 'nullable' : 'required', 'string', 'regex:/^temp_[a-zA-Z0-9]+$/'],
        ]);
        $type = ProductType::with('colors', 'positions')->findOrFail($validated['type_id']);
        $ids = array_map('intval', $validated['colors']);
        $errors = [];
        if (array_diff($ids, $type->colors->modelKeys())) {
            $errors['colors'] = 'Seleziona solo colori disponibili per questo tipo di prodotto.';
        }
        if (! in_array((int) $validated['default-color-id'], $ids, true)) {
            $errors['default-color-id'] = 'Il colore predefinito deve essere anche un colore attivo.';
        }
        if ($type->positions->isEmpty()) {
            $errors['type_id'] = 'Questo tipo di prodotto non ha posizioni di stampa disponibili.';
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        return $validated;
    }

    private function saveDetails(Product $product, array $validated, array $reference, ProductPriceCalculator $calculator): void
    {
        $product->colors()->sync(array_map('intval', $validated['colors']));
        ProductDescription::updateOrCreate(['product_id' => $product->id], ['description' => $validated['description'] ?? null]);
        $price = $calculator->calculate((float) $validated['price'], $reference['price'], $reference['gain_w'], $reference['gain_c']);
        ProductPrice::updateOrCreate(['product_id' => $product->id], ['price' => $price['price'], 'gain_w' => $price['earnings_for_light_garments'], 'gain_c' => $price['earnings_for_dark_garments']]);
    }

    private function saveImages(Request $request, Product $product, ImageProcessor $images): void
    {
        $files = [];
        if ($request->hasFile('cover-image')) {
            $files[] = ['field' => 'cover-image', 'file' => $request->file('cover-image'), 'path' => 'products/'.$product->id.'-cover.webp'];
        }
        foreach ($request->file('more-images', []) as $file) {
            $files[] = ['field' => 'more-images', 'file' => $file, 'path' => 'products/custom/'.$product->id.'-more-'.Str::uuid().'.webp'];
        }
        if ($files === []) {
            return;
        }

        $disk = Storage::disk('public');
        $staging = 'products/staging/'.Str::uuid();
        try {
            foreach ($files as $index => $file) {
                if (! $images->upload($file['file'], $staging.'/'.$index.'.webp', 1600)) {
                    throw ValidationException::withMessages([$file['field'] => 'Caricamento non riuscito. Riprova.']);
                }
            }
            foreach ($files as $index => $file) {
                if (! $disk->move($staging.'/'.$index.'.webp', $file['path'])) {
                    throw ValidationException::withMessages([$file['field'] => 'Impossibile salvare le immagini. Riprova.']);
                }
            }
        } finally {
            $disk->deleteDirectory($staging);
        }
    }

    public function delete(Product $product): RedirectResponse
    {
        CustomProductAccess::authorize($product);
        $product->delete();

        return to_route('seller-dashboard.portfolio')->with('success', 'Prodotto eliminato con successo.');
    }

    private function moveTemporaryProductImages(string $tempId, int $productId): void
    {
        $disk = Storage::disk('public');
        $directory = 'products/temp/'.$tempId;
        foreach ($disk->files($directory) as $source) {
            if (! $disk->move($source, 'products/custom/'.$productId.'-'.basename($source))) {
                throw ValidationException::withMessages(['images' => 'Impossibile salvare le immagini. Riprova.']);
            }
        }
        $disk->deleteDirectory($directory);
    }

    private function getPriceReferences(Collection $types, ?Product $product = null): array
    {
        $seller = Auth::user()->seller;
        $prices = SellerPrice::where('seller_id', $seller->id)->whereIn('type_id', $types->pluck('id')->all())->get()->keyBy('type_id');
        $basePrices = BasePrice::whereIn('type_id', $types->pluck('id')->all())->get()->keyBy('type_id');
        if ($product) {
            $product->loadMissing('specific_price', 'base_price');
        }

        return $types->mapWithKeys(function ($type) use ($prices, $basePrices, $product) {
            $price = $product && (int) $product->type_id === (int) $type->id
                ? ($product->specific_price ?? $prices->get($type->id) ?? $basePrices->get($type->id))
                : ($prices->get($type->id) ?? $basePrices->get($type->id));

            return [$type->id => [
                'price' => round((float) ($price?->price ?? 0), 2),
                'gain_w' => round((float) ($price?->gain_w ?? 0), 2),
                'gain_c' => round((float) ($price?->gain_c ?? 0), 2),
                'minimum_price' => $price ? max(0, app(ProductPriceCalculator::class)->minimumPrice($price->price, $price->gain_w, $price->gain_c)) : 0,
            ]];
        })->all();
    }
}
