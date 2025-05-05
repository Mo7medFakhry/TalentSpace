<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FileMedia;
use App\Models\Comment;
use App\Models\Like;
use App\Notifications\NewCommentNotification;
use App\Notifications\NewLikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoInteractionController extends Controller
{
    public function getComments(Request $request, FileMedia $fileMedia)
    {
        $comments = $fileMedia->comments()->with('user:id,name,profilePicture')->paginate(15);
        return response()->json($comments);
    }

    public function addComment(Request $request, FileMedia $fileMedia)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $comment = $fileMedia->comments()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        $comment->load('user:id,name,profilePicture');

        $videoOwner = $fileMedia->talent;
        if ($videoOwner && $videoOwner->id !== $user->id) {
            $videoOwner->notify(new NewCommentNotification($user, $comment, $fileMedia));
        }

        return response()->json($comment, 201);
    }

    public function deleteComment(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function toggleLike(Request $request, FileMedia $fileMedia)
    {
        $user = Auth::user();
        $like = $fileMedia->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
            $message = 'Video unliked successfully.';
        } else {
            $fileMedia->likes()->create(['user_id' => $user->id]);
            $liked = true;
            $message = 'Video liked successfully.';

            $videoOwner = $fileMedia->talent;
            if ($videoOwner && $videoOwner->id !== $user->id) {
                $videoOwner->notify(new NewLikeNotification($user, $fileMedia));
            }
        }

        $likeCount = $fileMedia->likes()->count();

        return response()->json([
            'message' => $message,
            'liked' => $liked,
            'like_count' => $likeCount
        ], 200);
    }

    public function getLikers(Request $request, FileMedia $fileMedia)
    {
        $likers = $fileMedia->likers()->select('users.id', 'users.name', 'users.profilePicture')->paginate(15);
        return response()->json($likers);
    }
}
