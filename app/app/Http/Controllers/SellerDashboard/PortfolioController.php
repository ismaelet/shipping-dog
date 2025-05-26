<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortfolioController extends Controller {

	/**
	 * Display the seller's portfolio.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$seller = Auth::user();

		$designs = Design::where('user_id', Auth::id())
			->where('status', '>', 0)
			->where('custom', 0)
			->orderBy('sort')
			->orderByDesc('id')
			->get();

		$customProducts = DB::select('id FROM products WHERE user_id = ? AND deleted = 0 AND (design_id = 0 OR design_id IN (SELECT id FROM designs WHERE custom = 1)) ORDER BY sort ASC, id DESC', [Auth::id()]);

		$designCount = $designs->count();
		$customProductsCount = $customProducts->num_rows;
		$totalCount = $designCount + $customProductsCount;

		return view('seller-dashboard.portfolio.index', [
			'totalCount' => $totalCount,
			'customProductsCount' => $customProductsCount,
			'designCount' => $designCount,
			'designs' => $designs,
			'seller' => $seller,
		]);
	}

	/**
	 * Display the page to add a new design to the portfolio.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function add(Request $request): View {
		// setup errors
		$error = false;

		// setup form
		$inputs = [
			[
				['type' => 'lbl', 'text' => 'Titolo della grafica'],
				[
					'name' => 'name',
					'text' => 'Titolo della grafica',
					'maxlenght' => 30,
					'required' => true,
				],
				['type' => 'error', 'error' => 'name-empty', 'text' => 'È obbligatario scegliere un nome',],
				['type' => 'error', 'error' => 'name-exists', 'text' => 'Esiste già una grafica con questo nome',],
			],
			[
				['type' => 'lbl', 'text' => 'Tag'],
				[
					'type' => 'textarea',
					'name' => 'tags',
					'text' => 'Tag (opzionali)',
					'pholder' => 'Puoi inserire fino a 10 tag separati da virgola. I tag rendono il tuo design più facile da trovare.',
					'maxlenght' => 160,
				],
			],
			[
				[
					'type' => 'file',
					'name' => 'file',
					'required' => true,
				],
			],
			[
				[
					'type' => 'lbl',
					'text' => 'File vettoriale (opzionale)',
				],
				[
					'type' => 'info',
					'text' => 'Anche se non è obbligatorio, consigliamo di caricare la grafica anche in formato <span class="font-semibold">PDF</span> o <span class="font-semibold">AI</span> per la <span class="text-primary">massima qualità di stampa</span> possibile.',
				],
				[
					'type' => 'file',
					'name' => 'file-vector',
					'accept' => '.pdf,.ai',
				],
			],
			[
				['type' => 'error', 'error' => 'file-size', 'text' => 'Il file caricato è troppo grande',],
				['type' => 'error', 'error' => 'file-type', 'text' => 'Tipo di file invalido, deve essere un <strong>PNG</strong>',],
				['type' => 'error', 'error' => 'file-reso', 'text' => 'Il file caricato deve avere una risoluzione minore di <strong>10.000px</strong>',],
				['type' => 'error', 'error' => 'error', 'text' => "C'è stato un errore durante il caricamento, se persite contattarci a <strong>" . config('custom.contacts.email.label') . "</strong>",],
			],
		];

		return view('seller-dashboard.portfolio.add', [
			'inputs' => $inputs,
			'error' => $error,
		]);
	}

	public function old() {
		if (isset($_POST['sort'])) {
			if (isset($_POST['id'])) {
				foreach ($_POST['id'] as $i => $design_id) {
					$pos = $_POST['pos'][$i];

					\Legacy\System\Classes\LegacyDB::update('designs SET sort=? WHERE id = ?', [$pos, $design_id]);
					\Legacy\System\Classes\LegacyDB::update('products SET sort=? WHERE design_id = ?', [$pos, $design_id]);
				}
			}

			if (isset($_POST['product-id'])) {
				foreach ($_POST['product-id'] as $i => $productId) {
					$productOrder = $_POST['product-order'][$i];

					\Legacy\System\Classes\LegacyDB::update('products SET sort=? WHERE id = ?', [$productOrder, $productId]);
				}
			}

			redirect_old();
		}
	}
}
