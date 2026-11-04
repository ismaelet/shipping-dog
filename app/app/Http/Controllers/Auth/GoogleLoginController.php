<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User as ContractsUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(Request $request): RedirectResponse
    {
        if ($request->filled('intended')) {
            $request->session()->put(
                'url.intended',
                $request->string('intended')->toString()
            );
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback(Request $request): RedirectResponse
    {
        // Google returns an error when the user denies or cancels login.
        if ($request->filled('error')) {
            Log::info('Google OAuth login was cancelled or rejected.', [
                'error' => $request->string('error')->toString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google login was cancelled. Please try again.',
                ]);
        }

        // Do not call Socialite without an authorization code.
        if (! $request->filled('code')) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'The Google login request was incomplete. Please try again.',
                ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $e) {
            Log::info('Invalid Google OAuth state.', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'has_code' => $request->filled('code'),
                'has_state' => $request->filled('state'),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'The Google login session expired. Please try again.',
                ]);
        } catch (RequestException $e) {
            Log::error('Google OAuth token request failed.', [
                'status' => $e->getResponse()?->getStatusCode(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google login could not be completed. Please try again.',
                ]);
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'Google login could not be completed. Please try again.',
                ]);
        }

        $intendedUrl = $request->session()->pull(
            'url.intended',
            route('user-dashboard')
        );

        $email = $googleUser->getEmail();

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = $this->registerUser($googleUser);
        }

        if (! $user->google_id) {
            $this->linkGoogleAccount($user, $googleUser);
        }

        Auth::login($user, remember: true);

        LoginController::mergeGuestCart();

        $request->session()->regenerate();

        return redirect()->intended($intendedUrl);
    }

    private function registerUser(ContractsUser $googleUser): User
    {
        $user = new User;
        $user->google_id = $googleUser->getId();
        $user->name = $googleUser->getName()
            ?: $googleUser->getNickname()
            ?: $googleUser->getEmail();
        $user->email = $googleUser->getEmail();
        $user->avatar = $googleUser->getAvatar();
        $user->password = Hash::make(str()->random(64));
        $user->save();

        return $user;
    }

    private function linkGoogleAccount(
        User $user,
        ContractsUser $googleUser
    ): User {
        $user->google_id = $googleUser->getId();
        $user->avatar = $googleUser->getAvatar();
        $user->save();

        return $user;
    }
}
