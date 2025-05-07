<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'role',
        'phone',
        'address',
        'bio',
        'birthday',
        'profilePicture',
        'social_id',
        'social_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships

    public function fileMedia()
    {
        return $this->hasMany(FileMedia::class, 'talent_id');
    }

    public function GivenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function ReceivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }


    public function offerAsInvestor()
    {
        return $this->hasMany(Offer::class, 'investor_id');
    }

    public function offerAsTalent()
    {
        return $this->hasMany(Offer::class, 'talent_id');
    }

    public function offerAsAdmin()
    {
        return $this->hasMany(Offer::class, 'admin_id');
    }

    public function achievementsAsTalent()
    {
        return $this->hasMany(Achievement::class, 'talent_id');
    }

    public function achievementsAsMentor()
    {
        return $this->hasMany(Achievement::class, 'mentor_id');
    }


//-----------------Followers---------

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function follow(User $user): void
    {
        $this->following()->attach($user->id);
    }

    public function unfollow(User $user): void
    {
        $this->following()->detach($user->id);
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('follower_id', $user->id)->exists();
    }



    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

//---------------------comments and likes --------------
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function likedMedia(): BelongsToMany
    {
        return $this->belongsToMany(FileMedia::class, 'likes', 'user_id', 'file_media_id')->withTimestamps();
    }

    // ---------- Offers ------------

    public function offersMade(): HasMany
    {
        return $this->hasMany(Offer::class, "investor_id");
    }

    /**
     * Get the offers received by the user (as a talent).
     */
    public function offersReceived(): HasMany
    {
        return $this->hasMany(Offer::class, "talent_id");
    }

    /**
     * Get the offers processed by the user (as an admin).
     */
    public function offersProcessed(): HasMany
    {
        return $this->hasMany(Offer::class, "admin_id");
    }
}
