<?php

use App\Livewire\FollowButton;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Follower;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->viewer = User::factory()->create();
    $this->seller = Seller::create([
        'user_id' => $this->owner->id,
        'name' => 'Follow test shop',
        'status' => true,
    ]);
    // Distinct IDs catch accidental use of the seller ID instead of its user ID.
    if (in_array($this->seller->id, [$this->owner->id, $this->viewer->id])) {
        $this->seller->id = max(Seller::max('id'), User::max('id')) + 100;
        $this->seller->save();
    }
    $this->pair = ['user_id' => $this->owner->id, 'follower_id' => $this->viewer->id];
});

it('persists follows across reloads without duplicating repeated requests', function () {
    Livewire::actingAs($this->viewer)->test(FollowButton::class, ['seller' => $this->seller])
        ->assertSet('isFollowing', false)
        ->assertSee('wire:click="follow"', false)
        ->call('follow')
        ->assertSet('isFollowing', true)
        ->assertSee('wire:click="unfollow"', false)
        ->call('follow');

    expect(Follower::where($this->pair)->count())->toBe(1);
    expect($this->seller->followers()->count())->toBe(1);
    expect($this->seller->isFollowed())->toBeTrue();

    Livewire::test(FollowButton::class, ['seller' => $this->seller->fresh()])
        ->assertSet('isFollowing', true)
        ->assertSee('wire:click="unfollow"', false);
});

it('unfollows only the current viewer and supports repeated follow and unfollow cycles', function () {
    Follower::create($this->pair);
    $other = User::factory()->create();
    $otherPair = ['user_id' => $this->owner->id, 'follower_id' => $other->id];
    Follower::create($otherPair);

    $component = Livewire::actingAs($this->viewer)->test(FollowButton::class, ['seller' => $this->seller])
        ->assertSet('isFollowing', true)
        ->call('unfollow')
        ->assertSet('isFollowing', false)
        ->assertSet('followerRecord', null)
        ->assertSee('wire:click="follow"', false)
        ->call('unfollow');

    $this->assertDatabaseMissing('followers', $this->pair);
    $this->assertDatabaseHas('followers', $otherPair);
    expect($this->seller->isFollowed())->toBeFalse();
    Livewire::test(FollowButton::class, ['seller' => $this->seller->fresh()])
        ->assertSet('isFollowing', false);

    $component->call('follow')->assertSet('isFollowing', true)
        ->call('unfollow')->assertSet('isFollowing', false);
    $this->assertDatabaseMissing('followers', $this->pair);
});

it('shows guests the login link and rejects direct mutation requests', function (string $action) {
    Livewire::test(FollowButton::class, ['seller' => $this->seller])
        ->assertSet('isAuthenticated', false)
        ->assertSee(route('login'))
        ->assertDontSee('wire:click', false)
        ->call($action)
        ->assertForbidden();

    $this->assertDatabaseMissing('followers', ['user_id' => $this->owner->id]);
})->with(['follow', 'unfollow']);

it('hides the button for owners and rejects self-follow actions', function (string $action) {
    Livewire::actingAs($this->owner)->test(FollowButton::class, ['seller' => $this->seller])
        ->assertSet('isVisible', false)
        ->assertDontSee('wire:click', false)
        ->call($action)
        ->assertForbidden();

    $this->assertDatabaseMissing('followers', ['user_id' => $this->owner->id]);
})->with(['follow', 'unfollow']);

it('handles an absent seller without rendering an action', function (string $action) {
    Livewire::actingAs($this->viewer)->test(FollowButton::class)
        ->assertSet('isVisible', false)
        ->assertSet('isFollowing', false)
        ->assertDontSee('wire:click', false)
        ->call($action)
        ->assertForbidden();
})->with(['follow', 'unfollow']);
