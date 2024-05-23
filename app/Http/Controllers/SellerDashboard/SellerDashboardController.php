<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SellerDashboardController extends Controller {

	/**
	 * Uploads the seller icon.
	 *
	 * @param Seller $seller The seller instance.
	 * @param Request $request The request object.
	 * @return RedirectResponse The redirect response.
	 */
	public function uploadSellerIcon(Seller $seller, Request $request): RedirectResponse {

		$request->validate([
			'icon' => ['required', 'mimes:jpeg,png,gif,webp'],
		]);

		FileHelper::uploadImage(
			file: $request->file('icon'),
			filePath: 'users/icons/' . $seller->user_id . '.webp',
			size: 252,
			mode: 'crop',
		);

		return redirect()->back();
	}

	/**
	 * Uploads a cover image for the seller's dashboard.
	 *
	 * @param Seller $seller The seller instance.
	 * @param Request $request The HTTP request object.
	 * @return RedirectResponse The redirect response.
	 */
	public function uploadSellerCover(Seller $seller, Request $request): RedirectResponse {

		$request->validate([
			'cover' => ['required', 'mimes:jpeg,png,gif,webp'],
		]);

		FileHelper::uploadImage(
			file: $request->file('cover'),
			filePath: 'shops/covers/' . $seller->user_id . '.webp',
			size: 1920,
			mode: 'width',
		);

		FileHelper::uploadImage(
			file: $request->file('cover'),
			filePath: 'shops/covers/' . $seller->user_id . '_m.webp',
			size: 400,
			mode: 'width',
		);

		return redirect()->back();
	}
}
