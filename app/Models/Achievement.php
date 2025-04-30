<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    //
    use HasFactory;


    protected $fillable = [
        'talent_id',
        'decision',
        'Type',
        'certification',
        'reviewMentor',
    ];


    // Relationships
    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }
}
