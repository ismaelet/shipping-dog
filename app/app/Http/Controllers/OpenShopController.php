<?php

namespace App\Http\Controllers;

use App\Legacy\System\DB;
use App\Models\Ecommerce\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OpenShopController extends Controller {

	/**
	 * Display the home page.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request): View {
		$title = 'Apri un negozio';
		$locationTag = 'open-shop';

		return view('open-shop', [
			'title' => $title,
			'locationTag' => $locationTag,
		]);
	}

	/**
	 * Create a new shop.
	 * 
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function add(Request $request): RedirectResponse {
		$name = trim($request->name);
		$desc = trim($request->description);

		if (empty($name)) {
			return redirect()->back()->withErrors(['name' => 'Il nome del negozio non può essere vuoto.']);
		}

		if (Seller::where('user_id', Auth::id())->exists()) {
			return redirect()->back()->withErrors(['name' => 'Hai già un negozio aperto.']);
		}

		Seller::create([
			'user_id' => Auth::id(),
			'name' => $name,
			'description' => $desc,
		]);

		return redirect()->route('seller.show')->with('success', 'Negozio creato con successo!');
	}
}
