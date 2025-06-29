<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'reviewee_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Only allow mentor-to-talent or talent-to-mentor
        $reviewer = auth()->user();
        $reviewee = User::find($request->reviewee_id);


        if ($reviewer->role === $reviewee->role) {
            return response()->json(['error' => 'Cannot review same role'], 403);
        }


        if (auth()->id() == $request->reviewee_id) {
            return response()->json(['error' => 'You cannot review yourself'], 403);
        }

        $review = Review::create([
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $request->reviewee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Review submitted', 'data' => $review]);
    }



    public function showReviews($userId)
    {
        $user = User::findOrFail($userId);

        $reviews = $user->ReceivedReviews()
            ->with(['reviewer:id,profilePicture,name,role']) // Include reviewer info
            ->get();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
            'reviews_count' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 2),
            'reviews' => $reviews
        ]);
    }

    /**
     * Show the best talents based on average review ratings.
     * Returns top 10 talents with their info, average rating, and review count.
     */
    public function bestTalents()
    {
        $talents = User::where('role', 'Talent')
            ->withCount(['ReceivedReviews as reviews_count'])
            ->withAvg('ReceivedReviews as average_rating', 'rating')
            ->orderByDesc('average_rating')
            ->orderByDesc('reviews_count')
            ->take(10)
            ->get(['id', 'name', 'profilePicture', 'role']);

        return response()->json([
            'talents' => $talents
        ]);
    }

}
