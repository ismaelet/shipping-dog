<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Legacy\Ecommerce\Seller as LegacySeller;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Seller;
use App\Models\User\UserContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller {

	/**
	 * Displays the seller dashboard.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index(): \Illuminate\View\View {
		$user = Auth::user();
		$seller = $user->seller;
		$noCover = strpos($seller->cover(), '0.jpg');

		return view('seller-dashboard.index', compact('user', 'seller', 'noCover'));
	}

	public function updateContacts(Seller $seller, Request $request): RedirectResponse {
		// Validate the request data for social media contacts
		$request->validate([
			'website' => 'nullable|url|max:255',
			'facebook' => 'nullable|string|max:255',
			'instagram' => 'nullable|string|max:255',
			'twitter' => 'nullable|string|max:255',
			'youtube' => 'nullable|string|max:255',
			'tiktok' => 'nullable|string|max:255',
			'bluesky' => 'nullable|string|max:255',
		]);

		// Format the URLs for social media contacts
		foreach (['facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'bluesky'] as $type) {
			if ($request->$type) {
				$request[$type] = $this->formatContactUrl($request[$type], $type);
			}
		}

		// Update or create the user contact information
		UserContact::updateOrCreate(
			['user_id' => $seller->user_id],
			$request->only(
				'website',
				'facebook',
				'instagram',
				'twitter',
				'youtube',
				'tiktok',
				'bluesky'
			)
		);

		return redirect()
			->back()
			->with('success_socials', 'Contatti aggiornati con successo');
	}

	private function formatContactUrl(string $url, string $type): string {
		$baseUrls = [
			'facebook' => 'https://facebook.com/',
			'instagram' => 'https://instagram.com/',
			'twitter' => 'https://twitter.com/',
			'youtube' => 'https://youtube.com/',
			'tiktok' => 'https://tiktok.com/@',
			'bluesky' => 'https://bsky.app/profile/',
		];

		if (str_starts_with($url, $baseUrls[$type])) {
			return $url;
		}

		$username = trim($url, "/@");
		$username = explode_last('/', $username)[1] ?? $username;

		return $baseUrls[$type] . $username;
	}

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
