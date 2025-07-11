<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\User;
use App\Notifications\OfferAcceptedByTalent;
use App\Notifications\OfferCreated;
use App\Notifications\OfferRejectedByTalent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    public function store(Request $request)
    {
        $investor = Auth::user();

        if ($investor->role !== "Investor") {
            return response()->json(["message" => "Unauthorized: Investor role required"], 403);
        }

        $validated = $request->validate([
            "title" => "required|string|max:255",
            "amount" => "required|integer|min:1",
            "notes" => "nullable|string",
            "talent_id" => [
                "required",
                "integer",
                Rule::exists("users", "id"),
                Rule::notIn([$investor->id]),
            ],
        ]);

        $offer = Offer::create([
            "investor_id" => $investor->id,
            "talent_id" => $validated["talent_id"],
            "title" => $validated["title"],
            "amount" => $validated["amount"],
            "notes" => $validated["notes"],
            "status" => "pendingAdminApproval",
        ]);

        // Notify admin(s)
        $admins = User::where("role", "Admin")->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new OfferCreated($offer, $investor));
        }

        return response()->json($offer, 201);
    }
    public function index(){
        $offers = Offer::all();
        return response()->json($offers);
}

    public function indexInvestor(Request $request)
    {
        $investor = Auth::user();
        $offers = $investor->offersMade()
                            ->with("talent:id,name,email,phone,profilePicture")
                            ->latest()
                            ->paginate(15);
        return response()->json($offers);
    }

    public function indexTalent(Request $request)
    {
        $talent = Auth::user();
        $offers = $talent->offersReceived()
                        ->where("status", "adminAccepted")
                        ->with("investor:id,name,email,phone,profilePicture")
                        ->latest()
                        ->paginate(15);
        return response()->json($offers);
    }

    public function respond(Request $request, Offer $offer)
    {
        $talent = Auth::user();

        if ($offer->talent_id !== $talent->id || $offer->status !== "adminAccepted") {
            return response()->json(["message" => "Unauthorized or invalid offer status"], 403);
        }

        $validated = $request->validate([
            "decision" => ["required", Rule::in(["accept", "reject"])],
        ]);

        if ($validated["decision"] === "accept") {
            $offer->status = "talentAccepted";
            $notification = new OfferAcceptedByTalent($offer, $talent);
        } else {
            $offer->status = "talentRejected";
            $notification = new OfferRejectedByTalent($offer, $talent);
        }

        $offer->save();

        $offer->investor->notify($notification);

        return response()->json($offer);
    }

    public function allOffersForTalent(Request $request)
    {
        $talent = Auth::user();

        if ($talent->role !== "Talent") {
            return response()->json(["message" => "Unauthorized: Talent role required"], 403);
        }

        $offers = $talent->offersReceived()
            ->whereIn("status" , ["adminAccepted", "talentAccepted", "talentRejected"])
            ->with("investor:id,name,email,phone,profilePicture")
            ->latest()
            ->paginate(15);

        return response()->json($offers);
    }
}
