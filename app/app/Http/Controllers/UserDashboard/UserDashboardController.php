<?php

namespace App\Http\Controllers\UserDashboard;

use App\Helpers\EcommerceHelper;
use App\Http\Controllers\Controller;
use App\Legacy\System\DB;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\User\FeedEntry;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller {

    /**
     * Display the user dashboard
     */
    public function index(Request $request): View {
        $title = 'Attività recenti';
        $user = user();

        $feedEntries = [];
        $feedEntryRows = DB::select(
            '
	order_items.id as event_id, 2 AS type, orders.created_at
		FROM order_items INNER JOIN orders ON order_items.order_id = orders.id WHERE seller_id = ? AND orders.status > 1
	UNION ALL
	SELECT id as event_id, 1 AS type, created_at
		FROM news
	ORDER BY created_at DESC
	LIMIT 15
	',
            [$user->id]
        );

        while ($feedEntryRow = $feedEntryRows->fetch_assoc()) {
            $feedEntries[] = new FeedEntry($feedEntryRow);
        }

        return view('user-dashboard.recap', [
            'location' => 'user-dashboard',
            'title' => $title,
            'feedEntries' => $feedEntries,
            'orderCount' => $user->orders()
                ->where('status', '!=', EcommerceHelper::statusFromTag('open'))
                ->count(),
            'wishlistCount' => $user->wishlist_items()->count(),
            'followedShopCount' => Follower::where('follower_id', $user->id)->count(),
        ]);
    }

    /**
     * Display the orders tab
     */
    public function orders(): View {
        return view('user-dashboard.orders', [
            'location' => 'user-dashboard',
            'title' => 'Ordini',
            'confirmedOrders' => $this->confirmedOrdersQuery()->paginate(10),
        ]);
    }

    /**
     * Download the list of the user's orders as a PDF
     */
    public function downloadOrders(Request $request): Response {
        $user = user();

        $html = view('user-dashboard.orders-pdf', [
            'user' => $user,
            'orders' => $this->confirmedOrdersQuery()->get(),
            'generatedAt' => Carbon::now()->locale('it')->isoFormat('D MMMM YYYY'),
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="storico-ordini.pdf"',
        ]);
    }

    /**
     * The user's confirmed orders, newest first
     */
    private function confirmedOrdersQuery(): Builder {
        return Order::where('user_id', user()->id)
            ->where('status', '!=', EcommerceHelper::statusFromTag('open'))
            ->with(
                'order_items',
                'order_items.type',
                'order_items.type.sizes',
                'order_items.size',
                'order_items.product_with_trashed',
            )
            ->orderByDesc('id');
    }

    /**
     * Display the settings tab
     *
     * @param Request $request
     * @return View
     */
    public function settings(Request $request): View {
        return view('user-dashboard.settings', [
            'location' => 'user-dashboard',
            'title' => 'Impostazioni',
        ]);
    }
}
