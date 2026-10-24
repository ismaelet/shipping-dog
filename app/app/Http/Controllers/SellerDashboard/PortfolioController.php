<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortfolioController extends Controller {

    /**
     * Action: Index, display the seller's portfolio page
     */
    public function index(): View {
        $seller = Auth::user()->seller;

        $designs = Design::where('user_id', Auth::id())
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        $customProducts = Product::where('user_id', Auth::id())->get();

        $customProducts = Product::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('design_id', false)
                    ->orWhereIn('design_id', function ($subQuery) {
                        $subQuery->select('id')
                            ->from('designs')
                            ->where('custom', true);
                    });
            })
            ->with('type', 'color', 'position', 'design_with_trashed')
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        $totalCount = $designs->count() + $customProducts->count();
        $isCustomSeller = (bool)($seller->custom || $customProducts->count());

        return view('seller-dashboard.portfolio', [
            'seller' => $seller,
            'isCustomSeller' => $isCustomSeller,
            'designs' => $designs,
            'customProducts' => $customProducts,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Display the page to add a new design to the portfolio.
     */
    public function add(): View {
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
                    'name' => 'file_vector',
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

    /**
     * Handle the sorting of portfolio items
     */
    public function sort(Request $request): RedirectResponse {
        $designIds = $request->input('id', []);
        $positions = $request->input('pos', []);

        // Update the sort order of each design
        foreach ($designIds as $index => $id) {
            Design::where('id', $id)->update(['sort' => $positions[$index]]);
        }

        return redirect()->back();
    }
}
