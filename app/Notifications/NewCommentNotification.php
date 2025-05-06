<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\FileMedia;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $commenter;
    public Comment $comment;
    public FileMedia $fileMedia;

    public function __construct(User $commenter, Comment $comment, FileMedia $fileMedia)
    {
        $this->commenter = $commenter;
        $this->comment = $comment;
        $this->fileMedia = $fileMedia;
    }

    public function via(object $notifiable): array
    {
        if ($this->commenter->id === $this->fileMedia->talent_id) {
            return [];
        }
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'commenter_id' => $this->commenter->id,
            'commenter_name' => $this->commenter->name,
            'commenter_picture' => $this->commenter->profilePicture ?? null,
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'file_media_id' => $this->fileMedia->id,
            'file_media_thumbnail' => $this->fileMedia->thumbnail,
            'message' => $this->commenter->name . ' commented on your video: "' . Str::limit($this->comment->body, 50) . '"',
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
