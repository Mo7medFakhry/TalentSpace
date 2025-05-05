<?php

namespace App\Notifications;

use App\Models\FileMedia;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $liker;
    public FileMedia $fileMedia;

    public function __construct(User $liker, FileMedia $fileMedia)
    {
        $this->liker = $liker;
        $this->fileMedia = $fileMedia;
    }

    public function via(object $notifiable): array
    {
        if ($this->liker->id === $this->fileMedia->talent_id) {
            return [];
        }
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_picture' => $this->liker->profilePicture ?? null,
            'file_media_id' => $this->fileMedia->id,
            'file_media_thumbnail' => $this->fileMedia->thumbnail,
            'message' => $this->liker->name . ' liked your video.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'created_at' => now()->toIso8601String(),
            'data' => $this->toDatabase($notifiable)
        ]);
    }
}
