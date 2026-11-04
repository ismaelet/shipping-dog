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
    public ?Seller $seller;

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
        $this->isFollowing = (bool) $this->seller->auth_follower?->id;
        $this->followerRecord = $this->seller->auth_follower;
    }

    /**
     * Follow the seller.
     */
    public function follow(): void
    {
        Follower::create([
            'follower_id' => $this->seller->id,
            'user_id' => Auth::id(),
        ]);

        $this->isFollowing = true;
    }

    /**
     * Unfollow the seller.
     */
    public function unfollow(): void
    {
        Follower::where([
            'follower_id' => $this->seller->id,
            'user_id' => Auth::id(),
        ])->delete();

        $this->isFollowing = false;
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        return view('livewire.follow-button');
    }
}
