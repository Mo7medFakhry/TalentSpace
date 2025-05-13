<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OfferAcceptedByTalent extends Notification implements ShouldQueue
{
    use Queueable;

    public Offer $offer;
    public User $talent;

    public function __construct(Offer $offer, User $talent)
    {
        $this->offer = $offer;
        $this->talent = $talent;
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
            "talent_name" => $this->talent->name,
            "talent_picture" => $this->talent->profilePicture,
            "message" => "Congratulations! Your offer '" . $this->offer->title . "' has been accepted by talent " . $this->talent->name . ".",
        ];
    }
}
