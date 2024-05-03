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
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller {

	/**
	 * * Create a new design
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
			'file' => ['required', 'image'],
			'file-vector' => 'mimes:pdf,ai',
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
			$colorIds = $type->publicColors()->pluck('id');
			$colorTags = $type->publicColors()->pluck('tag');
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
		$tags = strtolower(str_replace(['#'], '', $request->tags));
		$tags = explode(',', $tags);
		$tags = array_map('trim', $tags);
		$tags = array_filter($tags);
		$tags = array_unique($tags);
		$tags = array_slice($tags, 0, 10);

		$existingTags = Tag::whereIn('tag', $tags)->get()->pluck('tag')->toArray();

		foreach ($existingTags as $tag) {
			Tag::where('tag', $tag)->increment('count');
		}

		foreach ($tags as $tag) {
			Tag::create([
				'tag' => $tag,
				'count' => 1,
			]);
		}

		DesignTag::create([
			'design_id' => $design->id,
			'tags' => implode(',', $tags),
		]);

		// Store the original file
		FileHelper::uploadFile(request(), 'designs_originals/' . $design->id . '.png');

		// Store the usable HD file
		FileHelper::uploadImage([
			'path' => 'designs/' . $design->id . '.png',
			'size' => 1024,
			'type' => 'png',
		]);

		// Store the usable LD file
		FileHelper::uploadImage([
			'path' => 'designs/' . $design->id . '_m.png',
			'size' => 512,
			'type' => 'png',
		]);

		// Save the aspect ratio
		$imageSize = getimagesize($request->file('file')->getPathname());
		$ratio = round($imageSize[0] / $imageSize[1], 2);
		$design->update(['ratio' => $ratio]);

		// Upload the vector file
		if ($request->hasFile('file-vector')) {
			$vectorFile = $request->file('file-vector');
			$vectorFileExtension = $vectorFile->getClientOriginalExtension();
			$vectorFile->storeAs('designs_vector/' . $design->id . '.' . $vectorFileExtension, 'public');
		}

		// Redirect
		return to_route('designs.show', $design);
	}

	/**
	 * Show a design
	 */
	public function show(Design $design) {
		redirect_old('/manage/portfolio/edit?id=' . $design->id);
	}
}
