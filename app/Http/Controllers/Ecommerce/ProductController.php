<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Legacy\Classes\Ecommerce\Product as LegacyProduct;
use Legacy\System\Classes\LegacyDB;
use Legacy\System\Classes\LegacyView;

class ProductController extends Controller {

	/**
	 * * Show a specific product
	 */
	public function show(string $slug, Request $request): View|RedirectResponse {

		// get variabiles
		$id = $this->getIdFromSlug($slug);
		$legacyProductPositionId = $_GET['position_id'] ?? null;
		$legacyProductColorId = $_GET['color_id'] ?? null;

		// select item data
		$product = $id ? Product::find($id) : null;
		$legacyProduct = $id ? new LegacyProduct($id) : null;

		// Redirect to the shop it the product or seller was not found
		if (!$product || !$product->seller || !$product->status || $product->deleted) {
			redirect_old('/shop');
		}

		// redirect to the shop if the seller is not active and the user is not the seller or an admin
		if (!$product->seller->status && !$product->isUserOwner && !is_admin()) {
			redirect_old('/shop');
		}

		// get item seller
		$seller = $product->seller;

		// Set product properties
		if ($legacyProductPositionId) {
			$legacyProduct->setPositionId($legacyProductPositionId);
		}

		if ($legacyProductColorId) {
			$legacyProduct->setColorId($legacyProductColorId);
		}

		LegacyView::setThumbnail($legacyProduct->clientFullImage)
			->setTitle($legacyProduct->name);

		// redirect if not public
		$owner = $legacyProduct->isUserOwner;
		$vip = $owner || is_admin() ? true : false;

		$wip = !$seller->status;

		// select following status
		$foll = LegacyDB::exists('followers WHERE user_id = ? AND follower_id = ?', [auth()->id(), $legacyProduct->seller->id]);

		// select fan pics
		$res_fan = LegacyDB::select('* FROM home_pics WHERE design_id = ? ORDER BY id DESC LIMIT ?', [$legacyProduct->design->id, 5]);
		$fanN = $res_fan->num_rows;

		/**
		 * Related products
		 */
		$relatedGarments = rows_to_objects(
			LegacyDB::select(
				"items.id FROM items
		INNER JOIN product_types ON items.type_id = product_types.id
		WHERE design_id = ? AND type_id <> ? AND category='c' AND items.status = 1 AND items.deleted = 0
		ORDER BY type_id ASC, items.sort ASC, product_types.sort ASC, product_types.id ASC",
				[$legacyProduct->design->id, $legacyProduct->type->id]
			),
			LegacyProduct::class
		);

		$relatedAccessories = rows_to_objects(LegacyDB::select("id FROM items WHERE design_id = ? AND type_id <> ? AND type_id IN (SELECT id FROM product_types WHERE category='m') AND status = 1 AND deleted = 0 ORDER BY type_id ASC", [$legacyProduct->design->id, $legacyProduct->type->id]), LegacyProduct::class);

		$relatedProdutsVariables = [$seller->id, $legacyProduct->design->id, $legacyProduct->type->id, 8];
		$relatedProdutsVariables = array_merge($relatedProdutsVariables, $relatedProdutsVariables);

		$relatedGarmentCount = count($relatedGarments);
		$relatedAccessoryCount = count($relatedAccessories);

		/**
		 * Related products
		 */
		$relatedProducts = Product::where('user_id', $seller->id)
			->where(function ($query) use ($product) {
				$query->where('design_id', '<>', $product->design_id)
					->orWhere('design_id', 0);
			})
			->where('status', '>', 0)
			->where('type_id', $product->type_id)
			->where('deleted', 0)
			->orderBy('sales', 'desc')
			->orderBy('id', 'desc')
			->limit(8)
			->get();

		if ($relatedProducts->count() < 8) {
			$moreRelatedProducts = Product::where('user_id', $seller->id)
				->where(function ($query) use ($product) {
					$query->where('design_id', '<>', $product->design_id)
						->orWhere('design_id', 0);
				})
				->where('status', '>', 0)
				->where('type_id', '<>', $product->type_id)
				->where('deleted', 0)
				->orderBy('sales', 'desc')
				->orderBy('id', 'desc')
				->limit(8 - $relatedProducts->count())
				->get();

			$relatedProducts = $relatedProducts->merge($moreRelatedProducts);
		}

		// warnings
		$warning = warning_banner();

		return view('ecommerce.product')
			->with([
				'product' => $product,
				'legacyProduct' => $legacyProduct,
				'warning' => $warning,
				'relatedGarmentCount' => $relatedGarmentCount,
				'relatedGarments' => $relatedGarments,
				'relatedAccessoryCount' => $relatedAccessoryCount,
				'relatedAccessories' => $relatedAccessories,
			]);
	}

	/**
	 * * Add a product to the cart
	 */
	public function addToCart(string $slug, Request $request) {
		$config['product_id'] = $this->getIdFromSlug($slug);
		$config['quantity'] = $_POST['quantity'];

		if (isset($_POST['cbx-color'])) foreach ($_POST['cbx-color'] as $config['color_id']);
		if (isset($_POST['cbx-size'])) foreach ($_POST['cbx-size'] as $config['size_id']);
		if (isset($_POST['cbx-gender'])) foreach ($_POST['cbx-gender'] as $config['genderId']);

		if (isset($_POST['logo-id'])) {
			$config['logo_id'] = $_POST['logo-id'];
		}

		if (isset($_POST['cbx-pos'])) {
			foreach ($_POST['cbx-pos'] as $config['position_id']);
		} else {
			$config['position_id'] = $legacyProduct->availablePositionIds[0];
		}

		Order::add($config);

		redirect_old('/cart');
	}

	/**
	 * Get the id from the slug
	 */
	private function getIdFromSlug(string $slug): ?int {
		return explode('-', $slug)[0] ?? null;
	}
}
