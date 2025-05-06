<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
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

        public function comments(): HasMany
        {
            return $this->hasMany(Comment::class)->latest(); // Order comments by newest first
        }

        public function likes(): HasMany
        {
            return $this->hasMany(Like::class);
        }

        public function likers(): BelongsToMany
        {
            return $this->belongsToMany(User::class, 'likes', 'file_media_id', 'user_id')->withTimestamps();
        }

        public function isLikedBy(User $user): bool
        {
            // Check if the provided user exists in the collection of likers for this media
            // This check avoids an extra query if the relationship is already loaded.
            if ($this->relationLoaded('likers')) {
                return $this->likers->contains($user);
            }
            // Otherwise, perform the query
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
