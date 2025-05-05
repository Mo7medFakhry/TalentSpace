<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OfferCreated;
use App\Notifications\OfferStatusChanged;

class OfferController extends Controller
{
    /**
     * Create a new offer from investor to talent
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'talent_id' => 'required|exists:users,id',
        ]);

        // Check if the authenticated user is an investor
        if (!Auth::user()->hasRole('Investor')) {
            return response()->json(['message' => 'Only investors can create offers'], 403);
        }

        // Create the offer
        $offer = Offer::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'status' => 'pending', // Initial status
            'investor_id' => Auth::id(),
            'talent_id' => $request->talent_id,
        ]);

        // Notify admins about the new offer
        $admins = User::role('Admin')->get();
        Notification::send($admins, new OfferCreated($offer));

        return response()->json([
            'message' => 'Offer has been created successfully and is awaiting admin approval',
            'offer' => $offer
        ], 201);
    }

    /**
     * Admin reviews the offer
     */
    public function adminReview(Request $request, Offer $offer)
    {
        $request->validate([
            'status' => 'required|in:adminAccepted,adminRejected',
        ]);

        // Check if the authenticated user is an admin
        if (!Auth::user()->hasRole('Admin')) {
            return response()->json(['message' => 'Only admins can review offers'], 403);
        }

        // Update offer status
        $offer->status = $request->status;
        $offer->admin_id = Auth::id();
        $offer->save();

        // If admin rejected, notify investor
        if ($request->status === 'adminRejected') {
            $investor = User::find($offer->investor_id);
            $investor->notify(new OfferStatusChanged($offer, 'Your offer has been rejected by the admin.'));

            return response()->json([
                'message' => 'Offer has been rejected successfully',
                'offer' => $offer
            ]);
        }

        // If admin accepted, notify talent
        if ($request->status === 'adminAccepted') {
            $talent = User::find($offer->talent_id);
            $talent->notify(new OfferStatusChanged($offer, 'You have received a new offer! Please review it.'));

            return response()->json([
                'message' => 'Offer has been approved and sent to talent',
                'offer' => $offer
            ]);
        }
    }

    /**
     * Talent reviews the offer
     */
    public function talentReview(Request $request, Offer $offer)
    {
        $request->validate([
            'status' => 'required|in:talentAccepted,talentRejected',
        ]);

        // Check if the authenticated user is the talent for this offer
        if (Auth::id() != $offer->talent_id) {
            return response()->json(['message' => 'You are not authorized to review this offer'], 403);
        }

        // Check if offer was accepted by admin first
        if ($offer->status != 'adminAccepted') {
            return response()->json(['message' => 'This offer is not available for review'], 400);
        }

        // Update offer status
        $offer->status = $request->status;
        $offer->save();

        // Notify investor about talent's decision
        $investor = User::find($offer->investor_id);
        $message = $request->status === 'talentAccepted'
            ? 'Your offer has been accepted by the talent!'
            : 'Your offer has been rejected by the talent.';
        $investor->notify(new OfferStatusChanged($offer, $message));

        return response()->json([
            'message' => 'Offer has been ' . ($request->status === 'talentAccepted' ? 'accepted' : 'rejected') . ' successfully',
            'offer' => $offer
        ]);
    }

    /**
     * Get all offers for the authenticated user based on their role
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            // Admins see all pending offers or ones they've reviewed
            $offers = Offer::where('status', 'pending')
                ->orWhere('admin_id', $user->id)
                ->with(['investor', 'talent'])
                ->latest()
                ->get();
        } elseif ($user->hasRole('Investor')) {
            // Investors see only their own offers
            $offers = Offer::where('investor_id', $user->id)
                ->with(['talent'])
                ->latest()
                ->get();
        } elseif ($user->hasRole('Talent')) {
            // Talents see only admin-approved offers for them
            $offers = Offer::where('talent_id', $user->id)
                ->where('status', 'adminAccepted')
                ->orWhere(function($query) use ($user) {
                    $query->where('talent_id', $user->id)
                          ->whereIn('status', ['talentAccepted', 'talentRejected']);
                })
                ->with(['investor'])
                ->latest()
                ->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['offers' => $offers]);
    }

    /**
     * Get a specific offer
     */
    public function show(Offer $offer)
    {
        $user = Auth::user();

        // Check if user has access to this offer
        if ($user->hasRole('admin') ||
            $user->id === $offer->investor_id ||
            ($user->id === $offer->talent_id &&
             ($offer->status === 'adminAccepted' ||
              $offer->status === 'talentAccepted' ||
              $offer->status === 'talentRejected'))) {

            return response()->json(['offer' => $offer->load(['investor', 'talent', 'admin'])]);
        }

        return response()->json(['message' => 'You do not have permission to view this offer'], 403);
    }
}

