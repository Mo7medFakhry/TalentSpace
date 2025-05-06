<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = ['user_id', 'file_media_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileMedia()
    {
        return $this->belongsTo(FileMedia::class);
    }
}
