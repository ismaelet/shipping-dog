<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller {

    /**
     * Display the login view
     */
    public function index(): View {
        return view('auth.login')
            ->with('locationTag', 'login')
            ->with('title', 'Login');
    }

    /**
     * Handle an authentication attempt
     */
    public function auth(Request $request): RedirectResponse {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $login = $credentials['login'];
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Standard authentication attempt
        if (Auth::attempt([$fieldType => $login, 'password' => $password], $remember)) {
            $this->mergeGuestCart();
            $request->session()->regenerate();

            return redirect()->intended('user-dashboard');
        }

        // Legacy fallback for MD5 hashed passwords
        $user = User::query()
            ->where($fieldType, $login)
            ->first();

        if ($user && $this->isMD5($user->password) && hash_equals($user->password, md5($password))) {
            // Upgrade password to bcrypt
            $user->password = Hash::make($password);
            $user->save();

            Auth::login($user, $remember);

            $this->mergeGuestCart();
            $request->session()->regenerate();

            return redirect()->intended('user-dashboard');
        }

        // Authentication failed
        return back()->withErrors([
            'login' => 'Le credenziali fornite non sono corrette.',
        ])->onlyInput('login');
    }

    private function isMD5(string $hash): bool {
        return (bool) preg_match('/^[a-f0-9]{32}$/i', $hash);
    }

    /**
     * Merge the guest cart with the user cart.
     * If the user has a cart, merge the guest cart with the user cart by updating the order_id of each order in the guest cart to the user cart's id.
     * If the user does not have a cart, assign the guest cart to the user by updating the user_id of the guest cart to the authenticated user's id.
     * If there is no guest cart no action is taken.
     */
    private function mergeGuestCart(): void {
        if (session('order_id')) {
            // Find the guest cart
            $guestCart = Order::where('id', session('order_id'))
                ->where('status', 0)
                ->first();

            if ($guestCart) {
                // Find the user cart
                $userCart = Auth::user()->cart;

                if ($userCart) {
                    // If the user has a cart, merge the guest cart with the user cart
                    $guestCart->order_items->each(function (OrderItem $orderItem) use ($userCart) {
                        $orderItem->order_id = $userCart->id;
                        $orderItem->save();
                    });

                    $guestCart->delete();
                } else {
                    // If the user does not have a cart, assign the guest cart to the user
                    $guestCart->user_id = Auth::id();
                    $guestCart->save();
                }
            }
        }
    }
}
