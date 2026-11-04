<?php

namespace App\Livewire;

use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Follower;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class FollowButton extends Component
{
    /**
     * The seller to follow.
     */
    public ?Seller $seller = null;

    /**
     * Whether the button is visible.
     */
    public bool $isVisible = true;

    /**
     * Whether the user is authenticated.
     */
    public bool $isAuthenticated = false;

    /**
     * Whether the user is following the seller.
     */
    public bool $isFollowing = false;

    /**
     * The follower record for the authenticated user.
     */
    public ?Follower $followerRecord = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->isVisible = $this->seller && $this->seller->user_id != Auth::id();
        $this->isAuthenticated = Auth::check();
        $this->followerRecord = $this->isAuthenticated ? $this->seller?->auth_follower()->first() : null;
        $this->isFollowing = $this->followerRecord !== null;
    }

    /**
     * Follow the seller.
     */
    public function follow(): void
    {
        $this->authorizeFollow();

        $this->followerRecord = Follower::firstOrCreate([
            'user_id' => $this->seller->user_id,
            'follower_id' => Auth::id(),
        ]);

        $this->isFollowing = true;
    }

    /**
     * Unfollow the seller.
     */
    public function unfollow(): void
    {
        $this->authorizeFollow();

        Follower::where([
            'user_id' => $this->seller->user_id,
            'follower_id' => Auth::id(),
        ])->delete();

        $this->followerRecord = null;

        $this->isFollowing = false;
    }

    private function authorizeFollow(): void
    {
        abort_unless(Auth::check(), 403);
        abort_unless($this->seller && $this->seller->user_id != Auth::id(), 403);
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        return view('livewire.follow-button');
    }
}
