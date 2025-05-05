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
        'date',
        'city',
        'thumbnail',
    ];


    // Relationships
    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    // ------------- comments and likes -----------

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest(); // Order comments by newest first
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likers()
    {
        return $this->belongsToMany(User::class, 'likes', 'file_media_id', 'user_id')->withTimestamps();
    }

    public function isLikedBy(User $user): bool
    {
        if ($this->relationLoaded('likers')) {
            return $this->likers->contains($user);
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getTotalLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getTotalCommentsAttribute()
    {
        return $this->comments()->count();
    }
}
