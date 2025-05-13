<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OfferApprovedByAdmin extends Notification implements ShouldQueue
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
            "offer_amount" => $this->offer->amount,
            "investor_name" => $this->offer->investor->name,
            "investor_picture" => $this->offer->investor->profilePicture,
            "admin_name" => $this->admin->name,
            "message" => "Good news! You have received a new offer titled '" . $this->offer->title . "' from " . $this->offer->investor->name . ", approved by admin " . $this->admin->name . ". Please review and respond.",
        ];
    }
}
