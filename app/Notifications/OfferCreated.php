<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Offer;

class OfferCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected Offer $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'offer_id' => $this->offer->id,
            'investor_id' => $this->offer->investor_id,
            'amount' => $this->offer->amount,
            'title' => $this->offer->title,
            'investor_name' => optional($this->offer->investor)->name,
            'message' => 'New offer created by ' . optional($this->offer->investor)->name,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'created_at' => now()->toIso8601String(),
            'data' => [
                'offer_id' => $this->offer->id,
                'investor_id' => $this->offer->investor_id,
                'amount' => $this->offer->amount,
                'title' => $this->offer->title,
                'investor_name' => optional($this->offer->investor)->name,
                'message' => 'New offer created by ' . optional($this->offer->investor)->name,
            ]
        ]);
    }
}
