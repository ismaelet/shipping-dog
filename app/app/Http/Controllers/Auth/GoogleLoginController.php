<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as ContractsUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleLoginController extends Controller {

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(Request $request): RedirectResponse {
        if ($request->has('intended')) {
            $request->session()->put('url.intended', $request->input('intended'));
        }

        return Socialite::driver('google')
            ->redirect();
    }

    /**
     * Obtain the user information from Google
     */
    public function callback(Request $request): RedirectResponse {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            Log::info('Invalid Google OAuth state.', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'has_code' => $request->has('code'),
                'has_state' => $request->has('state'),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'The Google login session expired. Please try again.',
                ]);
        }

        $intendedUrl = session()->pull('url.intended', route('user-dashboard'));

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

        return redirect()->intended($intendedUrl);
    }

    private function registerUser(ContractsUser $googleUser): User {
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

    private function linkGoogleAccount(User $user, ContractsUser $googleUser): User {
        $user->google_id = $googleUser->getId();
        $user->avatar = $googleUser->getAvatar();
        $user->save();

        return $user;
    }
}
