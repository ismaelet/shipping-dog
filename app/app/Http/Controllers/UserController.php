<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Delete the user account
     */
    public function destroy()
    {
        $user = User::find(Auth::id());

        if ($user) {
            $user->delete();

            Auth::logout();
        }

        return redirect()->route('login')
            ->with('success', 'Il tuo account è stato eliminato con successo.');
    }
}
