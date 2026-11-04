<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    private const MIN_QUERY_LENGTH = 3;

    private const DESIGNS_PER_PAGE = 20;

    private const SELLERS_PER_PAGE = 24;

    private const SELLERS_PREVIEW = 6;

    private const TYPES = ['all', 'designs', 'sellers'];

    private const SORTS = ['relevance', 'popular', 'recent'];

    public function index(Request $request): View
    {
        $search = trim(mb_substr((string) $request->query('search', ''), 0, 100));
        $type = in_array($request->query('type'), self::TYPES, true) ? $request->query('type') : 'all';
        $sort = in_array($request->query('sort'), self::SORTS, true) ? $request->query('sort') : 'relevance';
        $isValid = mb_strlen($search) >= self::MIN_QUERY_LENGTH;

        $designs = null;
        $sellers = null;
        $designCount = 0;
        $sellerCount = 0;

        if ($isValid) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $designsQuery = $this->designsQuery($search, $like);
            $sellersQuery = $this->sellersQuery($search, $like);

            $designCount = (clone $designsQuery)->toBase()->getCountForPagination();
            $sellerCount = (clone $sellersQuery)->toBase()->getCountForPagination();

            if ($type !== 'sellers') {
                $designs = $this->sortDesigns($designsQuery, $search, $like, $sort)
                    ->paginate(self::DESIGNS_PER_PAGE)
                    ->withQueryString();
            }

            if ($type === 'sellers') {
                $sellers = $this->sortSellers($sellersQuery, $search, $like)
                    ->paginate(self::SELLERS_PER_PAGE)
                    ->withQueryString();
            } elseif ($type === 'all') {
                $sellers = $this->sortSellers($sellersQuery, $search, $like)
                    ->limit(self::SELLERS_PREVIEW)
                    ->get();
            }
        }

        return view('search', [
            'title' => 'Cerca: '.$search,
            'location' => 'search',
            'search' => $search,
            'type' => $type,
            'sort' => $sort,
            'isValid' => $isValid,
            'minLength' => self::MIN_QUERY_LENGTH,
            'designs' => $designs,
            'sellers' => $sellers,
            'designCount' => $designCount,
            'sellerCount' => $sellerCount,
        ]);
    }

    private function designsQuery(string $search, string $like): Builder
    {
        return Design::query()
            ->join('sellers', 'designs.user_id', '=', 'sellers.user_id')
            ->whereNull('sellers.deleted_at')
            ->where('sellers.status', true)
            ->where('sellers.hidden', false)
            ->where('designs.status', true)
            ->where('designs.hidden', false)
            ->whereHas('products')
            ->where(fn (Builder $query) => $query
                ->where('designs.name', 'like', $like)
                ->orWhere('sellers.name', 'like', $like)
                ->orWhereHas('tags', fn (Builder $tags) => $tags->where('tags', 'like', $like)));
    }

    private function sortDesigns(Builder $query, string $search, string $like, string $sort): Builder
    {
        $query->select('designs.*')
            ->with('main_product.type', 'main_product.color', 'main_product.position', 'main_product.design_with_trashed', 'seller')
            ->withCount('products');

        if ($sort === 'popular') {
            return $query->orderByDesc('designs.sales')->orderByDesc('designs.id');
        }

        if ($sort === 'recent') {
            return $query->orderByDesc('designs.created_at')->orderByDesc('designs.id');
        }

        $prefix = addcslashes($search, '%_\\').'%';

        return $query
            ->orderByRaw(
                'CASE WHEN designs.name = ? THEN 0 WHEN designs.name LIKE ? THEN 1 WHEN designs.name LIKE ? THEN 2 ELSE 3 END',
                [$search, $prefix, $like]
            )
            ->orderByDesc('designs.sales')
            ->orderByDesc('designs.id');
    }

    private function sellersQuery(string $search, string $like): Builder
    {
        return Seller::query()
            ->where('status', true)
            ->where('hidden', false)
            ->where('name', 'like', $like);
    }

    private function sortSellers(Builder $query, string $search, string $like): Builder
    {
        $prefix = addcslashes($search, '%_\\').'%';

        return $query
            ->withCount(['designs' => fn (Builder $designs) => $designs->where('status', true)->where('hidden', false)])
            ->orderByRaw('CASE WHEN name = ? THEN 0 WHEN name LIKE ? THEN 1 ELSE 2 END', [$search, $prefix])
            ->orderByDesc('sales')
            ->orderByDesc('id');
    }
}
