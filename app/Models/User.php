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

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function fileMedia()
    {
        return $this->hasMany(FileMedia::class, 'talent_id');
    }

    public function reviewRequestsGiven()
    {
        return $this->hasMany(ReviewRequest::class, 'reviewer_id');
    }

    public function reviewRequestsReceived()
    {
        return $this->hasMany(ReviewRequest::class, 'reviewed_id');
    }

    public function offerRequestsAsInvestor()
    {
        return $this->hasMany(OfferRequest::class, 'investor_id');
    }

    public function offerRequestsAsTalent()
    {
        return $this->hasMany(OfferRequest::class, 'talent_id');
    }

    public function achievementsAsTalent()
    {
        return $this->hasMany(Achievement::class, 'talent_id');
    }

    public function achievementsAsMentor()
    {
        return $this->hasMany(Achievement::class, 'mentor_id');
    }
}
