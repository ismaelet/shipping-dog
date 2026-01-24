<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller {

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(Request $request): RedirectResponse {
        return Socialite::driver('google')
            ->redirect();
    }

    /**
     * Obtain the user information from Google
     */
    public function callback(Request $request): RedirectResponse {
        $googleUser = Socialite::driver('google')->user();

        $email = $googleUser->getEmail();

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        // Register the user if not found
        if (!$user) {
            $user = $this->registerUser($googleUser);
        }

        // Link Google account if not linked
        if (!$user->google_id) {
            $this->linkGoogleAccount($user, $googleUser);
        }

        // Log the user in
        Auth::login($user, remember: true);

        // Merge guest cart
        LoginController::mergeGuestCart();

        $request->session()->regenerate();

        return redirect()->intended('user-dashboard');
    }

    private function registerUser($googleUser): User {
        $password = Hash::make(uniqid());

        $user = new User();
        $user->google_id = $googleUser->getId();
        $user->name = $googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail();
        $user->email = $googleUser->getEmail();
        $user->avatar = $googleUser->getAvatar();
        $user->password = $password;
        $user->save();

        return $user;
    }

    private function linkGoogleAccount(User $user, $googleUser): User {
        $user->google_id = $googleUser->getId();
        $user->avatar = $googleUser->getAvatar();
        $user->save();

        return $user;
    }
}
