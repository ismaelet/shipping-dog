<?php

namespace App\Models\User;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Seller;
use App\Models\News;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Vite;

class FeedEntry {

    public const INDEX = 'event_id';

    private $type_id;
    private $event_id;
    private $subjectId;

    /**
     * Property: The creation date of the feed entry
     */
    private DateTime $created_at;

    /**
     * Property: The order item associated with the feed entry (if applicable)
     */
    private ?OrderItem $orderItem = null;

    /**
     * Property: The user profile associated with the feed entry (if applicable)
     */
    private ?User $profile = null;

    /**
     * Property: The seller associated with the feed entry (if applicable)
     */
    private ?Seller $seller = null;

    /**
     * Property: The design associated with the feed entry (if applicable)
     */
    private ?Design $design = null;

    function __construct($row) {
        $this->type_id = $row['type'];
        $this->event_id = $row['event_id'] ?? null;
        $this->subjectId = $row['srcID'] ?? null;
        $this->created_at = new DateTime($row['created_at']);
        $this->orderItem = $this->orderItem();
        $this->profile = $this->profile();
        $this->seller = $this->seller();
        $this->design = $this->design();
    }

    private function news(): ?News {
        if ($this->type_id == 1) {
            return News::find($this->event_id);
        }

        return null;
    }

    private function profile(): ?User {
        if (in_array($this->type_id, [3, 4])) {
            return User::find($this->subjectId);
        }

        return null;
    }

    private function orderItem(): ?OrderItem {
        if ($this->type_id == 2) {
            return OrderItem::with('color', 'product', 'product.design')->find($this->event_id);
        }

        return null;
    }

    private function seller(): ?Seller {
        if ($this->type_id == 5) {
            return  $this->design->seller;
        }

        return null;
    }

    private function design(): ?Design {
        if ($this->type_id == 2) {
            return $this->orderItem->product?->design;
        }

        if (in_array($this->type_id, [3, 5])) {
            return Design::find($this->event_id);
        }

        return null;
    }

    public function text() {
        switch ($this->type_id) {
            case 1:
                $text = '<span class="font-semibold text-neutral-heading"><span class="text-primary">Shipping Dog</span></span>: ' . $this->news()->text;
                break;

            case 2:
                $text = $this->orderItem->product ?
                    'Hai venduto <span class="font-semibold text-neutral-heading">' . $this->orderItem->quantity . ' x</span> <a class="design" href="' . $this->orderItem->product->url() . '">' . $this->orderItem->product->name . '</a> per <span class="font-semibold text-neutral-heading">' . price($this->orderItem->total_seller_earnings_after_reduction) . '</span>'

                    :

                    'Hai venduto <span class="font-semibold text-neutral-heading">' . $this->orderItem->quantity . ' x</span> <span class="design">prodotto rimosso</span> per <span class="font-semibold text-neutral-heading">' . price($this->orderItem->total_seller_earnings_after_reduction) . '</span>';
                break;

            case 3:
                $text = '<span class="user">' . $this->profile->name . '</span> ha aggiunto <a class="design" href="' . $this->design->clientUrl . '">' . ($this->design->name ?: 'design rimosso') . '</a> ai preferiti';
                break;

            case 4:
                $text = '<span class="user">' . $this->profile->name . '</span> ha iniziato a seguirti';
                break;

            case 5:
                $text = '<a class="user" href="' . $this->seller->clientUrl . '">' . $this->seller->name . '</a> ha caricato un nuovo design: <a class="design" href="' . $this->design->clientUrl . '">' . ($this->design->name ?: 'design rimosso') . '</a>';
                break;

            default:
                $text = '';
        }

        $text = '<span class="font-semibold text-neutral-heading">' . $this->created_at->format('d-m-Y') . ':</span> ' . $text;

        return $text;
    }

    public function image() {
        switch ($this->type_id) {
            case 1:
                return Vite::image('user/icon.webp');
            case 2:
            case 5:
                return $this->orderItem->image();
            case 3:
            case 4:
                return $this->profile->icon();
            default:
                return null;
        }
    }
}
