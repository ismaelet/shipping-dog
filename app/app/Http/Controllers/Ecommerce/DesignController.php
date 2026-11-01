<?php

namespace App\Http\Controllers\Ecommerce;

use App\Actions\Designs\EditDesign;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignTag;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use App\Services\Files\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DesignController extends Controller
{
    public function __construct(
        private ImageProcessor $imageProcessor,
    ) {}

    /**
     * * Index action
     */
    public function index()
    {
        return redirect('/seller/portfolio');
    }

    /**
     * * Store action
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                $sameNameDesigns = Design::where('user_id', Auth::id())->where('name', $value)
                    ->where('status', 1);

                if ($sameNameDesigns->exists()) {
                    $fail('Hai già una grafica con questo nome.');
                }
            }],
            'file' => ['required', 'mimes:jpeg,png,webp'],
            'file_vector' => ['mimes:pdf,ai'],
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        // Create the design
        $design = Design::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'status' => 1,
        ]);

        // Add a logo group
        $sellerLogoGroup = LogoGroup::where('seller_id', Auth::id())->orderByDesc('id')->first();

        if ($sellerLogoGroup) {
            $design->update([
                'logo_group_id' => $sellerLogoGroup->id,
            ]);
        }

        // Create the products
        $types = ProductType::where('hidden', 0)->with('colors', 'positions')->get();

        foreach ($types as $type) {
            EditDesign::createProduct($design, $type);
        }

        // Add tags
        if ($request->tags) {
            $this->updateTagsHelper($design, $request->tags);
        }

        // Upload the image
        $this->uploadImageHelper($design, $request->file('file'));

        // Upload the vector file
        if ($request->hasFile('file_vector')) {
            $this->uploadVectorImage($design, $request->file('file_vector'));
        }

        // Redirect
        return to_route('designs.edit', $design);
    }

    /**
     * * Edit action
     */
    public function edit(?Design $design): View
    {
        abort_unless($design, 404);
        EditDesign::authorize($design);
        $hasLightVariant = Storage::disk('public')->exists('designs/'.$design->id.'.png');
        $hasDarkVariant = Storage::disk('public')->exists('designs/'.$design->id.'_dark.png');
        $hasVector = Storage::disk('public')->exists('designs_vector/'.$design->id.'.pdf') || Storage::disk('public')->exists('designs_vector/'.$design->id.'.ai');

        $title = 'Modifica: '.$design->name;

        return view('ecommerce.designs.edit', compact('design', 'title', 'hasLightVariant', 'hasDarkVariant', 'hasVector'));
    }

    /**
     * * Action: Destroy
     */
    public function destroy(Design $design): RedirectResponse
    {
        EditDesign::authorize($design);
        Product::where('design_id', $design->id)->delete();
        EditDesign::tags($design, null);
        $design->delete();

        return to_route('seller-dashboard.portfolio');
    }

    /**
     * Custom action: upload image
     */
    public function uploadImage(Design $design, Request $request): RedirectResponse
    {
        EditDesign::authorize($design);
        // Validate the request
        $request->validate([
            'type' => ['required', 'string', 'in:light,dark,vector'],
        ]);

        if ($request->type == 'vector') {
            // Validate the file type
            $request->validate(
                [
                    'file_vector' => ['required', 'mimes:pdf,ai'],
                ],
                [
                    'file_vector.mimes' => 'Il file deve essere un PDF o AI',
                ]
            );
            if (! $this->uploadVectorImage($design, $request->file('file_vector'))) {
                return back()->withErrors(['file_vector' => 'Caricamento non riuscito.']);
            }
        } else {
            // Validate the file type
            $inputName = $request->type == 'light' ? 'file_light' : 'file_dark';

            $request->validate([
                $inputName => ['required', 'image'],
            ], [
                $inputName.'.mimes' => 'Il file deve essere un\'immagine',
            ]);

            // Upload the image
            if (! $this->uploadImageHelper($design, $request->file($inputName), $request->type)) {
                return back()->withErrors([$inputName => 'Caricamento non riuscito.']);
            }
        }

        return back()->with(['#upload-image-'.$request->type => true]);
    }

    /**
     * Custom action: update tags
     */
    public function updateTags(Design $design, Request $request): RedirectResponse
    {
        EditDesign::authorize($design);
        $request->validate([
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        // Update the tags
        $this->updateTagsHelper($design, $request->tags);

        return back()->with(['saved-tags' => true, '#tags' => true]);
    }

    /**
     * Custom action: update background color
     */
    public function updateBackgroundColor(Design $design, Request $request): RedirectResponse
    {
        EditDesign::authorize($design);
        $request->validate([
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        EditDesign::background($design, $request->color);

        return back()->with(['saved-background-color' => true, '#background-color' => true]);
    }

    /**
     * Custom action: update default color
     */
    public function updateDefaultColor(Design $design, Request $request): RedirectResponse
    {
        EditDesign::authorize($design);
        $request->validate([
            'default_color_id' => ['required', 'integer', 'exists:product_colors,id'],
        ]);

        EditDesign::defaultColor($design, (int) $request->default_color_id);

        return back()->with(['saved-default-color' => true, '#default-color' => true]);
    }

    /**
     * Helper method: upload image
     */
    private function uploadImageHelper(Design $design, UploadedFile $file, string $variant = 'light'): bool
    {
        $design->load('products', 'products.color', 'products.type', 'products.position');
        $fileName = $variant === 'light' ? $design->id : $design->id.'_dark';

        // Save the aspect ratio
        $imageSize = getimagesize($file->getPathname());
        $ratio = round($imageSize[0] / $imageSize[1], 2);
        $design->update(['ratio' => $ratio]);

        // Store the original file
        if (
            ! FileHelper::upload($file, 'designs_originals/'.$fileName.'.png')
        ) {
            return false;
        }

        // Store the resized HD file
        if (
            ! $this->imageProcessor->upload(
                file: $file,
                path: 'designs/'.$fileName.'.png',
                size: 1024,
            )
        ) {
            return false;
        }

        // Store the resized LD file
        if (
            ! $this->imageProcessor->upload(
                file: $file,
                path: 'designs/'.$fileName.'_m.png',
                size: 512,
            )
        ) {
            return false;
        }

        EditDesign::invalidate($design);

        // Return true if every upload was successful
        return true;
    }

    /**
     * ~ Helper method: upload vector image
     */
    private function uploadVectorImage(Design $design, UploadedFile $file): bool
    {
        // Get the file extension
        $extension = strtolower($file->getClientOriginalExtension());

        // Store the file
        if (! $file->storeAs('designs_vector', $design->id.'.'.$extension, 'public')) {
            return false;
        }
        Storage::disk('public')->delete('designs_vector/'.$design->id.'.'.($extension === 'pdf' ? 'ai' : 'pdf'));

        return true;
    }

    /**
     * ~ Helper method: update tags
     */
    private function updateTagsHelper(Design $design, ?string $tags): ?DesignTag
    {
        EditDesign::tags($design, $tags);

        return $design->tags()->first();
    }
}
