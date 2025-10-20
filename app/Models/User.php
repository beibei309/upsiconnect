<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // extended fields
        'role',
        'phone',
        'profile_photo_path',
        'selfie_media_path',
        'public_verified_at',
        'verification_status',
        'staff_email',
        'staff_verified_at',
        'is_available',
        'is_suspended',
        'is_blacklisted',
        'blacklist_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // expose computed badge & rating
    protected $appends = ['trust_badge', 'average_rating'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'public_verified_at' => 'datetime',
            'staff_verified_at' => 'datetime',
            'is_available' => 'boolean',
            'is_suspended' => 'boolean',
            'is_blacklisted' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function studentServices()
    {
        return $this->hasMany(StudentService::class, 'user_id');
    }

    public function chatRequestsSent()
    {
        return $this->hasMany(ChatRequest::class, 'requester_id');
    }

    public function chatRequestsReceived()
    {
        return $this->hasMany(ChatRequest::class, 'recipient_id');
    }

    public function conversationsAsStudent()
    {
        return $this->hasMany(Conversation::class, 'student_id');
    }

    public function conversationsAsCustomer()
    {
        return $this->hasMany(Conversation::class, 'customer_id');
    }

    public function reviewsWritten()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    // Helpers
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isVerifiedPublic(): bool
    {
        return !is_null($this->public_verified_at) && $this->verification_status === 'approved';
    }

    public function isVerifiedStaff(): bool
    {
        return !is_null($this->staff_verified_at);
    }

    public function isAvailable(): bool
    {
        return (bool) $this->is_available;
    }

    public function getTrustBadgeAttribute(): string
    {
        if ($this->role === 'student' && $this->email_verified_at) {
            return 'Pelajar UPSI Terkini';
        }
        if ($this->isVerifiedStaff()) {
            return 'Staf UPSI Rasmi';
        }
        if ($this->isVerifiedPublic()) {
            return 'Pengguna Disahkan';
        }
        return 'Belum Disahkan';
    }

    public function getAverageRatingAttribute(): ?float
    {
        return $this->reviewsReceived()->avg('rating');
    }
}
