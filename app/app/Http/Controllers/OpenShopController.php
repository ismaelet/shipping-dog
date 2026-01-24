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
     * @param Request $request
     * @return View
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function add(Request $request): RedirectResponse {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $name = trim($request->name);
        $desc = trim($request->description);

        if (empty($name)) {
            return redirect()->back()->withErrors(['name' => 'Il nome del negozio non può essere vuoto.']);
        }

        if (Seller::where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors(['name' => 'Hai già un negozio aperto.']);
        }

        $seller = Seller::create([
            'user_id' => $user->id,
            'name' => $name,
            'description' => $desc,
        ]);

        if (!$user->name) {
            $user->update(['name' => $name]);
        }

        return redirect()->route('seller.show', ['seller' => $seller])->with('success', 'Negozio creato con successo!');
    }
}
