<?php

namespace App\Http\Controllers\Ecommerce;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller {

	/**
	 * Display a listing of the sales for the seller
	 */
	public function index(Request $request): View {
		$seller = $request->seller;
		$sort = $request->sort ?: 'time';
		$sort = in_array($sort, ['time', 'designs']) ?  $sort : 'time';
		$currentYear = $request->year;

		$sales = OrderItem::where('seller_id', $seller->id)
			->where('status', '>=', EcommerceHelper::statusFromTag('confirmed'))
			->select('*')
			->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
			->with('product', 'product.type', 'color', 'order')
			->latest()
			->get();

		return view('seller-dashboard.sales.index', compact('seller', 'sort', 'currentYear', 'sales'));
	}
}
