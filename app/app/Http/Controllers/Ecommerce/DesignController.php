<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignTag;
use App\Models\Ecommerce\Design\LogoGroup;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Product\ProductType;
use App\Models\Ecommerce\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DesignController extends Controller {

	/**
	 * * Index action
	 */
	public function index() {
		redirect_old('/manage/portfolio');
	}

	/**
	 * * Store action
	 */
	public function store(Request $request): RedirectResponse {
		// Validate the request
		$request->validate([
			'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
				$sameNameDesigns = Design::where('user_id', Auth::id())->where('name', $value)
					->where('status', 1);

				if ($sameNameDesigns->exists()) {
					$fail('Hai già una grafica con questo nome.');
				}
			}],
			'file' => ['required', 'mimes:jpeg,png,gif,webp'],
			'file-vector' => ['mimes:pdf,ai'],
			'tags' => ['nullable', 'string', 'max:255'],
		]);

		// Create the design
		$design = Design::create([
			'user_id' => Auth::id(),
			'name' => $request->name,
			'status' => 1,
		]);

		// Add a logo group
		$sellerLogoGroup = LogoGroup::where('seller_id', auth()->id())->orderByDesc('id')->first();

		if ($sellerLogoGroup) {
			$design->update([
				'logo_group_id' => $sellerLogoGroup->id,
			]);
		}

		// Create the products
		$types = ProductType::where('hidden', 0)->get();

		foreach ($types as $type) {
			$colorIds = $type->colors->pluck('id');
			$colorTags = $type->colors->pluck('tag');
			$positions = implode(',', $type->positions->pluck('id')->toArray());

			$color_id = $colorIds->first();
			$availableColorSlugs = implode(',', $colorTags->toArray());

			// Create the item
			Product::create([
				'name' => $design->name,
				'design_id' => $design->id,
				'user_id' => $design->user_id,
				'type_id' => $type->id,
				'color_id' => $color_id,
				'colors' => $availableColorSlugs,
				'positions' => $positions,
			]);
		}

		// Add tags
		if ($request->tags) {
			$this->updateTagsHelper($design, $request->tags);
		}

		// Upload the image
		$this->uploadImageHelper($design, $request->file('file'));

		// Upload the vector file
		if ($request->hasFile('file-vector')) {
			$this->uploadVectorImage($design, $request->file('file-vector'));
		}

		// Redirect
		return to_route('designs.edit', $design);
	}

	/**
	 * * Edit action
	 */
	public function edit(?Design $design): View {
		if (!$design || !$design->status || $design->user_id !== Auth::id()) {
			to_route('designs.index');
		}

		$title = 'Modifica: ' . $design->name;

		return view('ecommerce.designs.edit', compact('design', 'title'));
	}

	/**
	 * * Action: Destroy
	 */
	public function destroy(Design $design): RedirectResponse {
		$design->delete();

		return to_route('seller-dashboard.portfolio');
	}

	/**
	 * * Custom action: upload image
	 */
	public function uploadImage(Design $design, Request $request): RedirectResponse {
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

			// Upload the vector image
			$response = $this->uploadVectorImage($design, $request->file('file_vector'));
		} else {
			// Validate the file type
			$inputName = $request->type == 'light' ? 'file_light' : 'file_dark';

			$request->validate([
				$inputName => ['required', 'image'],
			], [
				$inputName . '.mimes' => 'Il file deve essere un\'immagine',
			]);

			// Upload the image
			$this->uploadImageHelper($design, $request->file($inputName), $request->type);
		}

		return back()->with(['#upload-image-' . $request->type => true]);
	}

	/**
	 * * Custom action: update tags
	 */
	public function updateTags(Design $design, Request $request): RedirectResponse {
		$request->validate([
			'tags' => ['nullable', 'string', 'max:255'],
		]);

		// Update the tags
		$this->updateTagsHelper($design, $request->tags);

		return back()->with(['saved-tags' => true, '#tags' => true]);
	}

	/**
	 * * Custom action: update background color
	 */
	public function updateBackgroundColor(Design $design, Request $request): RedirectResponse {
		$request->validate([
			'color' => ['required', 'string', 'size:7'],
		]);

		$design->update([
			'color' => $request->color,
		]);

		return back()->with(['saved-background-color' => true, '#background-color' => true]);
	}

	/**
	 * * Custom action: update default color
	 */
	public function updateDefaultColor(Design $design, Request $request): RedirectResponse {
		$request->validate([
			'default_color_id' => ['required', 'integer', 'exists:product_colors,id'],
		]);

		$design->update([
			'default_color_id' => $request->default_color_id,
		]);

		$design->products->each(function ($product) use ($request) {
			if ($product->type->colors->contains($request->default_color_id) && $product->colors()->contains($request->default_color_id)) {
				$product->update([
					'color_id' => $request->default_color_id,
				]);
			}
		});

		return back()->with(['saved-default-color' => true, '#default-color' => true]);
	}

	/**
	 * ~ Helper method: upload image
	 */
	private function uploadImageHelper(Design $design, UploadedFile $file, string $variant = 'light'): bool {
		$fileName = $variant === 'light' ? $design->id : $design->id . '_dark';

		// Save the aspect ratio
		$imageSize = getimagesize($file->getPathname());
		$ratio = round($imageSize[0] / $imageSize[1], 2);
		$design->update(['ratio' => $ratio]);

		// Store the original file
		if (
			!FileHelper::uploadFile($file, 'designs_originals/' . $fileName . '.png')
		) {
			return false;
		}

		// Store the resized HD file
		if (
			!FileHelper::uploadImage(
				file: $file,
				filePath: 'designs/' . $fileName . '.png',
				size: 1024,
			)
		) {
			return false;
		}

		// Store the resized LD file
		if (
			!FileHelper::uploadImage(
				file: $file,
				filePath: 'designs/' . $fileName . '_m.png',
				size: 512,
			)
		) {
			return false;
		}

		// Delete the old product images
		if (!$design->custom) {
			$design->products->each(function ($product) {
				$product->previewImage()?->deleteImages();
			});
		}

		// Delete old design images
		FileHelper::delete('designs/previews/' . $fileName . '.jpg');
		FileHelper::delete('designs/previews/' . $fileName . '_m.jpg');

		// Return true if every upload was successful
		return true;
	}

	/**
	 * ~ Helper method: upload vector image
	 */
	private function uploadVectorImage(Design $design, UploadedFile $file): bool {
		// Get the file extension
		$extension = $file->getClientOriginalExtension();

		// Store the file
		return $file->storeAs('designs_vector',  $design->id . '.' . $extension, 'public');
	}

	/**
	 * ~ Helper method: update tags
	 */
	private function updateTagsHelper(Design $design, ?string $tags): ?DesignTag {
		// Remove the previous tags
		if ($design->tags) {
			$previousTags = explode(',', $design->tags);

			foreach ($previousTags as $previousTag) {
				$tag = Tag::where('tag', $previousTag)->first();

				if ($tag && $tag->count > 1) {
					$tag->decrement('count');
				} else {
					$tag?->delete();
				}
			}
		}

		// Add the new tags
		if (!$tags) {
			DesignTag::where('design_id', $design->id)->delete();

			return null;
		}

		// Prepare the tags
		$tags = strtolower(str_replace(['#'], '', $tags));
		$tags = explode(',', $tags);
		$tags = array_map('trim', $tags);
		$tags = array_filter($tags);
		$tags = array_unique($tags);
		$tags = array_slice($tags, 0, 10);

		// Increment the count of existing tags
		$existingTags = Tag::whereIn('tag', $tags)->get()->pluck('tag')->toArray();

		foreach ($existingTags as $tag) {
			Tag::where('tag', $tag)->increment('count');
		}

		// Create new tags
		foreach ($tags as $tag) {
			Tag::create([
				'tag' => $tag,
				'count' => 1,
			]);
		}

		// Create the design tags
		return DesignTag::updateOrCreate([
			'design_id' => $design->id,
		], [
			'tags' => implode(',', $tags),
		]);
	}
}
