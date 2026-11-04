<?php

namespace App\Models\User;

use App\Models\Ecommerce\Design;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Seller;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Vite;

class FeedEntry
{
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

    public function __construct($row)
    {
        $this->type_id = $row['type'];
        $this->event_id = $row['event_id'] ?? null;
        $this->subjectId = $row['srcID'] ?? null;
        $this->created_at = new DateTime($row['created_at']);
        $this->orderItem = $this->orderItem();
        $this->profile = $this->profile();
        $this->seller = $this->seller();
        $this->design = $this->design();
    }

    private function news(): ?News
    {
        if ($this->type_id == 1) {
            return News::find($this->event_id);
        }

        return null;
    }

    private function profile(): ?User
    {
        if (in_array($this->type_id, [3, 4])) {
            return User::find($this->subjectId);
        }

        return null;
    }

    private function orderItem(): ?OrderItem
    {
        if ($this->type_id == 2) {
            return OrderItem::with('color', 'product', 'product.design')->find($this->event_id);
        }

        return null;
    }

    private function seller(): ?Seller
    {
        if ($this->type_id == 5) {
            return $this->design->seller;
        }

        return null;
    }

    private function design(): ?Design
    {
        if ($this->type_id == 2) {
            return $this->orderItem->product?->design;
        }

        if (in_array($this->type_id, [3, 5])) {
            return Design::find($this->event_id);
        }

        return null;
    }

    public function title(): string
    {
        switch ($this->type_id) {
            case 1:
                return 'Novità da Shipping Dog';
            case 2:
                return 'Hai venduto '.(int) $this->orderItem->quantity.' x '.$this->link($this->orderItem->product?->url(), $this->orderItem->product?->name ?: 'prodotto rimosso');
            case 3:
                return e($this->profile->name).' ha aggiunto un design ai preferiti';
            case 4:
                return e($this->profile->name).' ha iniziato a seguirti';
            case 5:
                return $this->link($this->seller->clientUrl, $this->seller->name).' ha caricato un nuovo design';
            default:
                return '';
        }
    }

    public function subtitle(): string
    {
        switch ($this->type_id) {
            case 1:
                return (string) $this->news()?->text;
            case 2:
                return 'Guadagno: '.price($this->orderItem->total_seller_earnings_after_reduction);
            case 3:
            case 5:
                return $this->link($this->design->clientUrl, $this->design->name ?: 'design rimosso');
            default:
                return '';
        }
    }

    /**
     * Relative date: "Oggi", "Ieri", "2 giorni fa"...
     */
    public function date(): string
    {
        $date = Carbon::instance($this->created_at)->locale('it');

        if ($date->isToday()) {
            return 'Oggi';
        }

        if ($date->isYesterday()) {
            return 'Ieri';
        }

        return $date->diffForHumans(now(), ['parts' => 1]);
    }

    private function link(?string $url, string $label): string
    {
        $label = e($label);

        return $url ? '<a class="text-secondary-400 hover:underline" href="'.e($url).'">'.$label.'</a>' : $label;
    }

    public function image()
    {
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
