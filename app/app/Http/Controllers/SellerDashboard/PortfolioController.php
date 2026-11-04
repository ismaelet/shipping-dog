<?php

namespace App\Http\Controllers\SellerDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $seller = Auth::user()->seller;

        $activeTab = $request->routeIs('seller-dashboard.portfolio.add')
            || $request->query('tab') === 'upload';

        return view('seller-dashboard.portfolio', [
            'seller' => $seller,
            'activeTab' => $activeTab,
        ]);
    }
}
