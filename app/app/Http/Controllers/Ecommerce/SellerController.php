<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Design\DesignCollection;
use App\Models\Ecommerce\Product;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\User\Like;
use App\Services\Files\ImageProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function __construct(private ImageProcessor $imageProcessor) {}

    /**
     * Display the seller page
     */
    public function index(): View|RedirectResponse
    {
        $id = request()->route('seller')
            ?? request()->query('seller')
            ?? request()->query('id');

        $seller = Seller::query()
            ->with('user')
            ->find($id);

        if (! $seller) {
            return redirect()->route('shop');
        }

        $owner = Auth::id() !== null
            && Auth::id() === (int) $seller->user_id;

        if (! $seller->status && ! $owner && ! is_admin()) {
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

        if (! $owner) {
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
    public function addCollection(Request $request, Seller $seller): RedirectResponse
    {
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
    public function showCollection(Seller $seller, DesignCollection $collection): View|RedirectResponse
    {
        if ($collection->user_id !== $seller->user_id) {
            return redirect()->route('shop');
        }

        $userIsOwner = Auth::check() && Auth::id() === $seller->user_id;

        if (! $seller->status && ! $userIsOwner && ! is_admin()) {
            return redirect()->route('shop');
        }

        $designs = collect();
        $otherCollections = collect();

        if ($userIsOwner) {
            $designs = Design::query()
                ->where('user_id', $seller->user_id)
                ->where('status', 1)
                ->orderByDesc('id')
                ->get(['id', 'name', 'collection']);

            $otherCollections = DesignCollection::query()
                ->where('user_id', $seller->user_id)
                ->where('id', '!=', $collection->id)
                ->pluck('name', 'id');
        }

        if ($userIsOwner) {
            $designCount = $designs->where('collection', $collection->id)->count();
        } else {
            $designCount = Design::query()
                ->where('user_id', $seller->user_id)
                ->where('collection', $collection->id)
                ->where('status', 1)
                ->count();
        }

        return view('ecommerce.seller.collection', [
            'title' => $collection->name,
            'thumbnail' => $collection->image(1024),
            'location' => 'shop',
            'seller' => $seller,
            'collection' => $collection,
            'productCount' => $collection->purchasableProducts()->count(),
            'designCount' => $designCount,
            'designs' => $designs,
            'otherCollections' => $otherCollections,
            'userIsOwner' => $userIsOwner,
        ]);
    }

    /**
     * Set the designs of a collection. Designs already in another collection are moved.
     */
    public function syncCollectionDesigns(
        Request $request,
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (! $this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $data = $request->validate([
            'designs' => ['nullable', 'array'],
            'designs.*' => [
                'integer',
                'distinct',
                Rule::exists('designs', 'id')->where('user_id', $seller->user_id)->whereNull('deleted_at'),
            ],
        ]);

        $selected = collect($data['designs'] ?? [])->map(fn ($id) => (int) $id);

        $designs = Design::query()->where('user_id', $seller->user_id);

        $previousCollections = (clone $designs)
            ->whereIn('id', $selected)
            ->where('collection', '>', 0)
            ->where('collection', '!=', $collection->id)
            ->pluck('collection')
            ->unique();

        (clone $designs)
            ->where('collection', $collection->id)
            ->whereNotIn('id', $selected)
            ->update(['collection' => 0]);

        (clone $designs)
            ->whereIn('id', $selected)
            ->update(['collection' => $collection->id]);

        // status is not mass assignable on the model
        DesignCollection::whereKey($collection->id)->update(['status' => $selected->isEmpty() ? 0 : 2]);
        $this->closeEmptyCollections($seller, $previousCollections);

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
        if (! $this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $file = $request->file('image');
        $width = getimagesize($file->getRealPath())[0] ?? 0;
        $base = 'shops/collections/'.$collection->id;

        // Never upscale: the processor resizes to the requested width exactly.
        $uploaded = $this->imageProcessor->upload($file, $base.'.webp', min(2560, $width), 'width')
            && $this->imageProcessor->upload($file, $base.'_m.webp', min(400, $width), 'width');

        if (! $uploaded) {
            return back()->withErrors(['image' => 'Impossibile elaborare l\'immagine. Prova con un altro file.']);
        }

        // Remove legacy jpg covers so the new one is always used.
        FileHelper::delete($base.'.jpg');
        FileHelper::delete($base.'_m.jpg');

        return back();
    }

    /**
     * Delete a seller collection. Its designs and products are kept.
     */
    public function deleteCollection(
        Seller $seller,
        DesignCollection $collection,
    ): RedirectResponse {
        if (! $this->canManageCollection($seller, $collection)) {
            abort(403);
        }

        Design::query()
            ->where('user_id', $seller->user_id)
            ->where('collection', $collection->id)
            ->update(['collection' => 0]);

        $collection->deleteCover();
        $collection->delete();

        return redirect()->route('seller.show', $seller);
    }

    /**
     * Set status to 0 for collections that no longer contain designs.
     */
    private function closeEmptyCollections(Seller $seller, Collection $collectionIds): void
    {
        $stillUsed = Design::query()
            ->where('user_id', $seller->user_id)
            ->whereIn('collection', $collectionIds)
            ->distinct()
            ->pluck('collection');

        DesignCollection::query()
            ->where('user_id', $seller->user_id)
            ->whereIn('id', $collectionIds->diff($stillUsed))
            ->update(['status' => 0]);
    }

    /**
     * Check whether the current user can manage the collection.
     */
    private function canManageCollection(Seller $seller, DesignCollection $collection): bool
    {
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
        if (! $this->canManageCollection($seller, $collection)) {
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
