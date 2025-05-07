<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OfferRejectedByAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public Offer $offer;
    public User $admin;

    public function __construct(Offer $offer, User $admin)
    {
        $this->offer = $offer;
        $this->admin = $admin;
    }

    public function via(object $notifiable): array
    {
        return ["database", "broadcast"];
    }

    public function toArray(object $notifiable): array
    {
        return [
            "offer_id" => $this->offer->id,
            "offer_title" => $this->offer->title,
            "talent_name" => $this->offer->talent->name,
            "admin_name" => $this->admin->name,
            "message" => "Unfortunately, your offer '" . $this->offer->title . "' for " . $this->offer->talent->name . " was rejected by admin " . $this->admin->name . ".",
        ];
    }
}
