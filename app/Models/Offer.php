<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "title",
        "amount",
        "notes",
        "status",
        "investor_id",
        "talent_id",
        "admin_id", // Include admin_id if you want to set it directly sometimes
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Optional: Cast amount to integer if needed, though it's defined as integer in migration
        // 'amount' => 'integer',
    ];

    /**
     * Get the investor (user) who created the offer.
     */
    public function investor(): BelongsTo
    {
        return $this->belongsTo(User::class, "investor_id");
    }

    /**
     * Get the talent (user) to whom the offer is directed.
     */
    public function talent(): BelongsTo
    {
        return $this->belongsTo(User::class, "talent_id");
    }

    /**
     * Get the admin (user) who processed the offer.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, "admin_id");
    }
}
