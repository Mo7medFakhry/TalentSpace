<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferRequest extends Model
{
    //
    use HasFactory;

    // Relationships
    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }
}
