<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $follower;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    // public function toDatabase(object $notifiable): array
    // {
    //     return [
    //         'follower_id' => $this->follower->id,
    //         'follower_name' => $this->follower->name,
    //         'follower_picture' => $this->follower->profile_photo_url ?? null,
    //         'message' => $this->follower->name . 'Started following you.',
    //     ];
    // }
    public function toDatabase(object $notifiable): array
    {
        // Log::info('✅ Notification toDatabase called');

        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_picture' => $this->follower->profilePicture ,
            'message' => $this->follower->name . ' Started following you.',
        ];
    }


    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'created_at' => now()->toIso8601String(),
            'data' => [
                'follower_id' => $this->follower->id,
                'follower_name' => $this->follower->name,
                'follower_picture' => $this->follower->profilePicture,
                'message' => $this->follower->name . ' Started following you.',
            ]
        ]);
    }
}
