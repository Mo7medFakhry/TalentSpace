<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Offer;

class OfferStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $offer;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Offer $offer, $message)
    {
        $this->offer = $offer;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->line($this->message)
            ->line('Offer Title: ' . $this->offer->title)
            ->line('Amount: $' . $this->offer->amount);

        // Add appropriate action link based on user type
        if ($notifiable->id == $this->offer->talent_id && $this->offer->status == 'adminAccepted') {
            $mailMessage->action('Review Offer', url('/talent/offers/' . $this->offer->id));
        } elseif ($notifiable->id == $this->offer->investor_id) {
            $mailMessage->action('View Offer', url('/investor/offers/' . $this->offer->id));
        }

        return $mailMessage->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'offer_id' => $this->offer->id,
            'title' => $this->offer->title,
            'message' => $this->message,
            'status' => $this->offer->status,
        ];
    }
}
