<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileMedia extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'title',
        'description',
        'video',
        'tags',
        'Status',
    ];


    // Relationships
    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }
}
