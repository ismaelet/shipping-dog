<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Render the logout page
     */
    public function index()
    {
        return view('auth.logout')
            ->with('location', 'logout')
            ->with('title', 'Logout');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();

        return redirect()->route('login');
    }
}
