<?php

namespace App\Livewire\Seller;

use App\Mail\SellerActivationRequestMail;
use App\Models\Ecommerce\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ActivationRequest extends Component
{
    /**
     * Property: The seller model instance
     */
    public Seller $seller;

    /**
     * Property: Whether the activation request component is visible
     */
    public bool $visible = false;

    /**
     * Lifecycle hook: Mount the component
     */
    public function mount()
    {
        $this->visible = ! $this->seller->status && Auth::id() === $this->seller->user_id;
    }

    /**
     * Lifecycle hook: Render the component view
     */
    public function render()
    {
        return view('livewire.seller.activation-request');
    }

    /**
     * Action: Send activation request email
     */
    public function sendActivationRequest()
    {
        Mail::to(config('custom.notification_emails'))
            ->send(new SellerActivationRequestMail($this->seller));

        $this->seller->activation_request_sent = true;
        $this->seller->save();
    }
}
