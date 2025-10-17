<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductColor;
use App\Models\Ecommerce\Product\ProductDescription;
use App\Models\Ecommerce\Product\ProductPrice;
use App\Models\Ecommerce\Product\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortfolioController extends Controller {

    /**
     * Action: Index, display the seller's portfolio page
     */
    public function index(Request $request): View {
        $seller = Auth::user()->seller;

        $designs = Design::where('user_id', Auth::id())
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        $customProducts = Product::where('user_id', Auth::id())->get();

        $customProducts = Product::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('design_id', false)
                    ->orWhereIn('design_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('designs')
                            ->where('custom', true);
                    });
            })
            ->with('type', 'color', 'position', 'design_with_trashed')
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        $totalCount = $designs->count() + $customProducts->count();
        $isCustomSeller = (bool)($seller->custom || $customProducts->count());

        return view('seller-dashboard.portfolio.index', [
            'seller' => $seller,
            'isCustomSeller' => $isCustomSeller,
            'designs' => $designs,
            'customProducts' => $customProducts,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Display the page to add a new design to the portfolio.
     */
    public function add(Request $request): View {
        // setup errors
        $error = false;

        // setup form
        $inputs = [
            [
                ['type' => 'lbl', 'text' => 'Titolo della grafica'],
                [
                    'name' => 'name',
                    'text' => 'Titolo della grafica',
                    'maxlenght' => 30,
                    'required' => true,
                ],
                ['type' => 'error', 'error' => 'name-empty', 'text' => 'È obbligatario scegliere un nome',],
                ['type' => 'error', 'error' => 'name-exists', 'text' => 'Esiste già una grafica con questo nome',],
            ],
            [
                ['type' => 'lbl', 'text' => 'Tag'],
                [
                    'type' => 'textarea',
                    'name' => 'tags',
                    'text' => 'Tag (opzionali)',
                    'pholder' => 'Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare.',
                    'maxlenght' => 160,
                ],
            ],
            [
                [
                    'type' => 'file',
                    'name' => 'file',
                    'required' => true,
                ],
            ],
            [
                [
                    'type' => 'lbl',
                    'text' => 'File vettoriale (opzionale)',
                ],
                [
                    'type' => 'info',
                    'text' => 'Anche se non è obbligatorio, consigliamo di caricare la grafica anche in formato <span class="font-semibold">PDF</span> o <span class="font-semibold">AI</span> per la <span class="text-primary">massima qualità di stampa</span> possibile.',
                ],
                [
                    'type' => 'file',
                    'name' => 'file_vector',
                    'accept' => '.pdf,.ai',
                ],
            ],
            [
                ['type' => 'error', 'error' => 'file-size', 'text' => 'Il file caricato è troppo grande',],
                ['type' => 'error', 'error' => 'file-type', 'text' => 'Tipo di file invalido, deve essere un <strong>PNG</strong>',],
                ['type' => 'error', 'error' => 'file-reso', 'text' => 'Il file caricato deve avere una risoluzione minore di <strong>10.000px</strong>',],
                ['type' => 'error', 'error' => 'error', 'text' => "C'è stato un errore durante il caricamento, se persite contattarci a <strong>" . config('custom.contacts.email.label') . "</strong>",],
            ],
        ];

        return view('seller-dashboard.portfolio.add', [
            'inputs' => $inputs,
            'error' => $error,
        ]);
    }

    /**
     * Handle the sorting of portfolio items
     */
    public function sort(Request $request): RedirectResponse {
        $designIds = $request->input('id', []);
        $positions = $request->input('pos', []);

        // Update the sort order of each design
        foreach ($designIds as $index => $id) {
            Design::where('id', $id)->update(['sort' => $positions[$index]]);
        }

        return redirect()->back();
    }

    /**
     * Display the form to add a custom product
     */
    public function create(): View {
        $productId = $_GET['id'] ?? null;
        $product = Product::find($productId);

        // Fetch types
        $types = ProductType::where('id', '<>', 16)->orderBy('sort')->get();

        // Fetch colors
        $colors = ProductColor::orderBy('reorder')->get();

        return view('seller-dashboard.portfolio.products.show', [
            'product' => $product,
            'types' => $types,
            'colors' => $colors,
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

        return view('seller-dashboard.portfolio.products.show', [
            'product' => $product,
            'types' => $types,
            'colors' => $colors,
        ]);
    }

    /**
     * Handle the submission of the custom product form
     */
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:product_types,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'default-color-id' => 'required|integer|exists:product_colors,id',
            'colors' => 'array',
            'cover-image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $type = ProductType::find($request->input('type_id'));
        $price = str_replace(',', '.', $request->input('price'));
        $description = $request->input('description');
        $defaultColor = ProductColor::find($request->input('default-color-id'));
        $newColors = implode(',', $request->input('colors', [$defaultColor->tag]));
        $position = $type->positions ? $type->positions->first() : null;

        $product = Product::create([
            'name' => $request->input('name'),
            'design_id' => false,
            'user_id' => Auth::id(),
            'type_id' => $request->input('type_id'),
            'color_id' => $defaultColor->id,
            'positions' => $position->id,
            'colors' => $newColors,
            'status' => true,
        ]);

        // upload product images
        ProductDescription::updateOrCreate(
            ['product_id' => $product->id],
            ['description' => $description]
        );

        // Price
        ProductPrice::updateOrCreate(
            ['product_id' => $product->id],
            [
                'price' => $price,
                'gain_w' => $product->price()->gain_w,
                'gain_c' => $product->price()->gain_c,
            ]
        );

        // Update cover image
        if ($request->file('cover-image')) {
            ImageHelper::upload(
                file: $request->file('cover-image'),
                path: 'products/custom/' . $product->id . '-cover.webp',
                size: 1920,
                mode: 'width',
                quality: 80
            );
        }

        return redirect()->route('seller-dashboard.portfolio.products.show', $product)->with('success', 'Il prodotto è stato creato con successo.');
    }

    /**
     * Handle the submission of the custom product form
     */
    public function update(Request $request, Product $product): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:product_types,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'default-color-id' => 'required|integer|exists:product_colors,id',
            'colors' => 'array',
            'cover-image' => 'nullable|image|max:5120', // Max 5MB
        ]);

        $name = $request->input('name');
        $typeId = $request->input('type_id');
        $type = ProductType::find($typeId);
        $price = str_replace(',', '.', $request->input('price'));
        $description = $request->input('description');
        $defaultColor = ProductColor::find($request->input('default-color-id'));
        $newColors = implode(',', $request->input('colors', [$defaultColor->tag]));
        $positionId = $type->positions ? explode(',', $type->positions)[0] : null;

        // edit
        $product->update([
            'name' => $name,
            'type_id' => $typeId,
            'color_id' => $defaultColor->id,
            'colors' => $newColors
        ]);

        ProductDescription::updateOrCreate(
            ['product_id' => $product->id],
            ['description' => $description]
        );

        // Price
        ProductPrice::updateOrCreate(
            ['product_id' => $product->id],
            [
                'price' => $price,
                'gain_w' => $product->price()->gain_w,
                'gain_c' => $product->price()->gain_c,
            ]
        );

        // Update cover image
        if ($_FILES['cover-image']['name']) {
            ImageHelper::upload(
                file: request()->file('cover-image'),
                path: 'products/custom/' . $product->id . '-cover.webp',
                size: 1920,
                mode: 'width',
                quality: 80
            );
        }

        return redirect()->route('seller-dashboard.portfolio.products.show', $product)->with('success', 'Il prodotto è stato aggiornato con successo.');
    }

    /**
     * Upload product image for a specific color
     */
    public function uploadProductImage(Request $request, Product $product, ProductColor $color): RedirectResponse {
        $request->validate([
            'file-' . $color->id => 'required|image|max:10240',
        ]);

        $files = [
            ['size' => 1024, 'quality' => 90],
            ['size' => 400, 'quality' => 80],
        ];

        foreach ($files as $file) {
            ImageHelper::upload(
                file: $request->file('file-' . $color->id),
                path: 'products/custom/' . $product->id . '-' . $color->tag . '-1-' . $file['size'] . '.webp',
                size: $file['size'],
                mode: 'largest',
                quality: $file['quality']
            );
        }

        return redirect()->back()->with('success', 'Immagine caricata con successo.');
    }

    /**
     * Delete a custom product
     */
    public function delete(Product $product): RedirectResponse {
        $product->delete();

        return redirect()->route('seller-dashboard.portfolio')->with('success', 'Prodotto eliminato con successo.');
    }
}
