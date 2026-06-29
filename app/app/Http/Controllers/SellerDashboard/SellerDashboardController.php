<?php

namespace App\Http\Controllers\SellerDashboard;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Seller;
use App\Models\User\UserContact;
use App\Services\Files\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller {

    public function __construct(
        private ImageProcessor $imageProcessor,
    ) {
    }

    /**
     * Displays the seller dashboard.
     *
     * @return View
     */
    public function index(): View {
        $user = Auth::user();
        $seller = $user->seller;
        $noCover = strpos($seller->cover(), '0.jpg');

        return view('seller-dashboard.index', compact('user', 'seller', 'noCover'));
    }

    /**
     * Updates the seller's settings.
     *
     * @param Seller $seller The seller instance.
     * @param Request $request The request object.
     * @return RedirectResponse The redirect response.
     */
    public function update(Seller $seller, Request $request): RedirectResponse {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'sort' => 'required|integer|min:1|max:3',
        ]);

        // Update the seller's name and description
        $seller->update($request->only('name', 'description', 'sort'));

        return redirect()
            ->back()
            ->with('success_settings', 'Impostazioni aggiornate con successo');
    }

    /**
     * Updates the seller's contact information.
     * 
     * @param Seller $seller The seller instance.
     * @param Request $request The request object containing the contact information.
     * @return RedirectResponse The redirect response.
     */
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

    /**
     * Formats the contact URL for social media platforms.
     *
     * @param string $url The URL to format.
     * @param string $type The type of social media platform.
     * @return string The formatted URL.
     */
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
        $username = basename(rtrim($username, '/'));

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
            'icon' => ['required', 'mimes:jpeg,png,webp'],
        ]);

        $this->imageProcessor->upload(
            file: $request->file('icon'),
            path: 'users/icons/' . $seller->user_id . '.webp',
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
            'cover' => ['required', 'mimes:jpeg,png,webp'],
        ]);

        $this->imageProcessor->upload(
            file: $request->file('cover'),
            path: 'shops/covers/' . $seller->user_id . '.webp',
            size: 1920,
            mode: 'width',
        );

        $this->imageProcessor->upload(
            file: $request->file('cover'),
            path: 'shops/covers/' . $seller->user_id . '_m.webp',
            size: 400,
            mode: 'width',
        );

        return redirect()->back();
    }
}
