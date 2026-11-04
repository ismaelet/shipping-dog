<?php

namespace App\Http\Controllers;

use App\Models\Ecommerce\Design\CustomerPhoto;
use App\Models\Ecommerce\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request): View
    {
        $fanPicsCount = 5;

        // Products
        $productRelations = [
            'design',
            'design_with_trashed',
            'design.seller',
            'seller',
            'seller.prices',
            'design.logo_group',
            'color',
            'type',
            'position',
            'specific_price',
            'base_price',
        ];

        $productQuery = fn () => Product::query()
            ->join('sellers', 'products.user_id', '=', 'sellers.user_id')
            ->where('products.status', true)
            ->where('sellers.status', true)
            ->with($productRelations)
            ->select('products.*');

        // Top 2 bestselling products
        $bestSellers = $productQuery()
            ->orderByDesc('products.recent_sales')
            ->orderByDesc('products.id')
            ->limit(2)
            ->get();

        // Top 2 newest products with at least one recent sale
        $recentProducts = $productQuery()
            ->where('products.recent_sales', '>=', 1)
            ->whereNotIn('products.id', $bestSellers->pluck('id'))
            ->orderByDesc('products.created_at')
            ->orderByDesc('products.id')
            ->limit(2)
            ->get();

        // Combine both collections, preserving their order
        $popular = $bestSellers->concat($recentProducts);

        // customer pics
        $customerPhotos = CustomerPhoto::inRandomOrder()
            ->limit($fanPicsCount)
            ->with('design', 'design.main_product')
            ->get();

        // categories
        $features = [
            [
                'title' => 'Creato da artisti',
                'description' => 'Design originali da creativi indipendenti.',
                'icon' => 'artist',
            ],
            [
                'title' => 'Stampato in Italia',
                'description' => 'Produzione responsabile e di qualità.',
                'icon' => 'shirt',
            ],
            [
                'title' => 'Resi semplici',
                'description' => 'Hai 30 giorni per cambiare idea.',
                'icon' => 'return',
            ],
        ];

        return view('home', [
            'products' => $popular,
            'customerPhotos' => $customerPhotos,
            'features' => $features,
            'location' => 'home',
        ]);
    }
}
