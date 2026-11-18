<?php

namespace Tests\Feature;

use App\Filament\Admin\Resources\Sellers\SellerResource;
use App\Models\Ecommerce\Seller;
use App\Models\Ecommerce\Seller\Payment;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;
use Tests\TestCase;

class SellerResourceFormTest extends TestCase
{
    public function test_payment_details_are_hidden_when_creating_a_seller(): void
    {
        $this->assertFalse($this->paymentDetailsAreVisible(null));
    }

    public function test_payment_details_follow_the_existing_sellers_payment_request(): void
    {
        $seller = new Seller;
        $seller->setRelation('payment_request', null);
        $this->assertFalse($this->paymentDetailsAreVisible($seller));

        $seller->setRelation('payment_request', new Payment);
        $this->assertTrue($this->paymentDetailsAreVisible($seller));
    }

    private function paymentDetailsAreVisible(?Seller $seller): bool
    {
        $livewire = new class extends Component implements HasSchemas
        {
            use InteractsWithSchemas;
        };
        $schema = SellerResource::form(Schema::make($livewire)->model($seller ?? Seller::class));
        $section = $schema->getComponents(withHidden: true)[0];
        $paymentDetails = $section->getChildSchema()->getComponents(withHidden: true)[1];

        return $paymentDetails->isVisible();
    }
}
