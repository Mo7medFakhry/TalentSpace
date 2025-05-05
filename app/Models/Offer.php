<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    use HasFactory;
    protected $fillable = [
        "title",
        "amount",
        "notes",
        "status",
        "investor_id",
        "talent_id",
        "admin_id",
    ];

    public function investor()
    {
        return $this->belongsTo(User::class, "investor_id");
    }

    public function talent()
    {
        return $this->belongsTo(User::class, "talent_id");
    }


    public function admin()
    {
        return $this->belongsTo(User::class, "admin_id");
    }

}
