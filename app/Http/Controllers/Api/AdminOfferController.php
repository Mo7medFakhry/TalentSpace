<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Notifications\OfferApprovedByAdmin;
use App\Notifications\OfferRejectedByAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminOfferController extends Controller
{

    private function checkAdminRole()
    {
        if (Auth::guest() || Auth::user()->role !== "Admin") {
            abort(403, "Unauthorized: Admin role required");
        }
    }
    public function indexPending(Request $request)
    {
        $this->checkAdminRole();
        $offers = Offer::where("status", "pendingAdminApproval")
                        ->with(["investor:id,name", "talent:id,name"])
                        ->latest()
                        ->paginate(15);
        return response()->json($offers);
    }

    public function decide(Request $request, Offer $offer)
    {
        $this->checkAdminRole();
        $admin = Auth::user();

        if ($offer->status !== "pendingAdminApproval") {
            return response()->json(["message" => "Offer is not pending approval"], 422);
        }

        $validated = $request->validate([
            "decision" => ["required", Rule::in(["approve", "reject"])],
        ]);

        if ($validated["decision"] === "approve") {
            $offer->status = "adminAccepted";
            $notification = new OfferApprovedByAdmin($offer, $admin);
            $recipient = $offer->talent;
        } else {
            $offer->status = "adminRejected";
            $notification = new OfferRejectedByAdmin($offer, $admin);
            $recipient = $offer->investor;
        }

        $offer->admin_id = $admin->id;
        $offer->save();

        $recipient->notify($notification);

        return response()->json($offer);
    }
}
