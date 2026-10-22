<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\User\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SellerController extends Controller {

    /**
     * Display the seller page
     */

    public function index(): View|RedirectResponse {
        $id = request()->route('seller')
            ?? request()->query('seller')
            ?? request()->query('id');

        $seller = Seller::query()
            ->with('user')
            ->find($id);

        if (!$seller) {
            return redirect()->route('shop');
        }

        $owner = Auth::id() !== null
            && Auth::id() === (int) $seller->user_id;

        if (!$seller->status && !$owner && !is_admin()) {
            return redirect()->route('shop');
        }

        $sellerUserId = $seller->user_id;
        $collBlock = 10;

        // Statistics
        $productCount = $seller->products()
            ->where('status', 1)
            ->count();

        $likeN = Like::query()
            ->whereIn('product_id', Product::query()
                ->select('id')
                ->where('user_id', $sellerUserId))
            ->count();

        $follN = Follower::query()
            ->where('user_id', $sellerUserId)
            ->count();

        // Collections
        $collections = DesignCollection::query()
            ->where('user_id', $sellerUserId);

        if (!$owner) {
            $collections->whereIn(
                'id',
                Design::query()
                    ->select('collection')
                    ->where('user_id', $sellerUserId)
            );
        }

        $collN = $collections->count();

        return view('ecommerce.seller', [
            'title' => $seller->name,
            'description' => $seller->description,
            'locationTag' => 'shop',
            'seller' => $seller,
            'user' => $seller->user,
            'owner' => $owner,
            'contacts' => [
                'website',
                'facebook',
                'instagram',
                'twitter',
                'youtube',
                'tiktok',
                'bluesky',
            ],
            'productCount' => $productCount,
            'likeN' => $likeN,
            'follN' => $follN,
            'collN' => $collN,
            'hasItems' => $productCount > 0,
            'collBlock' => $collBlock,
        ]);
    }


    /**
     * Add a new collection for the seller
     */
    public function addCollection(Request $request, Seller $seller): RedirectResponse {
        if (Auth::id() !== $seller->user_id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $collection = DesignCollection::create([
            'user_id' => $seller->user_id,
            'name' => trim($data['name']),
        ]);

        return redirect()->route('seller.show.collection', [
            'seller' => $seller,
            'collection' => $collection,
        ]);
    }

    /**
     * Display a collection of the seller.
     */
    public function showCollection(Seller $seller, DesignCollection $collection): View|RedirectResponse {
        if ($collection->user_id !== $seller->user_id) {
            return redirect()->route('shop');
        }

        $userIsOwner = Auth::check() && Auth::id() === $seller->user_id;
        $vip = $userIsOwner || is_admin();

        if (!$seller->status && !$vip) {
            return redirect()->route('shop');
        }

        $products = Product::query()
            ->where('status', 1)
            ->where('deleted', 0)
            ->whereHas('design', function ($query) use ($seller, $collection) {
                $query->where('user_id', $seller->user_id)
                    ->where('collection', $collection->id);
            })
            ->with('type', 'color', 'design_with_trashed', 'base_price', 'specific_price', 'seller')
            ->orderByDesc('sales')
            ->orderByDesc('design_id')
            ->orderBy('id')
            ->limit(100)
            ->get();

        $designsToAdd = collect();
        $designsToRemove = collect();

        if ($userIsOwner) {
            $designsToAdd = Design::query()
                ->where('user_id', $seller->user_id)
                ->where('collection', '!=', $collection->id)
                ->where('status', 1)
                ->orderByDesc('id')
                ->get();

            $designsToRemove = Design::query()
                ->where('user_id', $seller->user_id)
                ->where('collection', $collection->id)
                ->where('status', 1)
                ->orderByDesc('id')
                ->get();
        }

        return view('ecommerce.seller.collection', [
            'title' => $collection->name,
            'description' => $collection->description,
            'thumbnail' => $collection->image(1024),
            'location' => 'shop',
            'seller' => $seller,
            'collection' => $collection,
            'products' => $products,
            'designsToAdd' => $designsToAdd,
            'designsToRemove' => $designsToRemove,
            'userIsOwner' => $userIsOwner,
            'vip' => $vip,
        ]);
    }

    /**
     * Add designs to a seller collection.
     */
    public function addDesignsToCollection(
        Request $request,
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (!$this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $data = $request->validate([
            'designs' => ['nullable', 'array'],
            'designs.*' => ['integer'],
        ]);

        $designIds = $data['designs'] ?? [];

        if (!empty($designIds)) {
            Design::query()
                ->where('user_id', $seller->user_id)
                ->whereIn('id', $designIds)
                ->update([
                    'collection' => $collection->id,
                ]);

            if (!$collection->status) {
                $collection->update([
                    'status' => 2,
                ]);
            }
        }

        return back();
    }

    /**
     * Remove designs from a seller collection.
     */
    public function removeDesignsFromCollection(
        Request $request,
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (!$this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $data = $request->validate([
            'designs' => ['nullable', 'array'],
            'designs.*' => ['integer'],
        ]);

        $designIds = $data['designs'] ?? [];

        if (!empty($designIds)) {
            Design::query()
                ->where('user_id', $seller->user_id)
                ->where('collection', $collection->id)
                ->whereIn('id', $designIds)
                ->update([
                    'collection' => 0,
                ]);
        }

        $hasDesigns = Design::query()
            ->where('user_id', $seller->user_id)
            ->where('collection', $collection->id)
            ->exists();

        if (!$hasDesigns) {
            $collection->update([
                'status' => 0,
            ]);
        }

        return back();
    }

    /**
     * Update the collection cover image.
     */
    public function updateCollectionCover(
        Request $request,
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (!$this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $request->validate([
            'image' => ['required', 'image', 'max:8192'],
        ]);

        // Temporary simple version.
        // You can replace this with your existing ImageHelper if you need resizing.
        $request->file('image')->storeAs(
            'shops/collections',
            $collection->id . '.jpg',
            'public'
        );

        return back();
    }

    /**
     * Delete a seller collection.
     */
    public function deleteCollection(
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (!$this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        Design::query()
            ->where('collection', $collection->id)
            ->update([
                'collection' => 0,
            ]);

        $collection->delete();

        return redirect()->route('seller.show', $seller);
    }

    /**
     * Check whether the current user can manage the collection.
     */
    private function canManageCollection(Seller $seller, DesignCollection $collection): bool {
        if ($collection->user_id !== $seller->user_id) {
            return false;
        }

        return Auth::check() && Auth::id() === $seller->user_id;
    }

    /**
     * Update the collection name.
     */
    public function updateCollectionName(
        Request $request,
        Seller $seller,
        DesignCollection $collection,
    ): JsonResponse {
        if (!$this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $collection->update([
            'name' => trim($data['name']),
        ]);

        return response()->json([
            'success' => true,
            'name' => $collection->name,
        ]);
    }
}
