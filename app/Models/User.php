<?php

namespace App\Models;

use App\Models\Office;
use App\Models\Pdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Authenticatable implements CanResetPasswordContract
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
        'designation',
        'office_id',
        'email',
        'password',
        'status',
        'role',
        'signature', // added for profile image URL
        'authorization_letter',
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

    /**
     * The attributes that should be cast.
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

    /**
     * Check if the user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if the user is an administrator
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrator';
    }
    public function isPresentativeStaff(): bool
{
    return $this->role === 'presentative_staff';
}

        public function isHead(): bool
    {
        return $this->role === 'head';
    }

    public function isPgso(): bool
{
    return $this->role === 'pgso';
}

    /**
     * Check if the user is an end-user
     */
    public function isEndUser(): bool
    {
        return $this->role === 'end_user';
    }


    public function office()
{
    return $this->belongsTo(Office::class);
}
    public function scopeActive($query)
{
    return $query->where('status', 'active');
}

public function authorizes()
{
    return $this->hasMany(Authorize::class);
}

public function requests()
{
    return $this->hasMany(Requests::class, 'user_id');
}

public function receivedRequests()
{
    return $this->hasMany(Requests::class, 'requested_to');
}

public function pdfs()
{
    return $this->hasMany(Pdf::class);
}

/**
 * Generate a deterministic secure token for authorization letter URL
 */
public function getAuthorizationLetterTokenAttribute(): string
{
    // Derive a unique 32-char hex token using app key, user ID, created_at timestamp and file path
    $raw = $this->id . '|' . $this->created_at . '|' . ($this->authorization_letter ?? '') . '|' . config('app.key');
    return substr(hash('sha256', $raw), 0, 32);
}

/**
 * Find user by ID or by matching authorization letter token
 */
public static function findByAuthLetterIdentifier(string $identifier): ?self
{
    // If integer ID was passed (fallback for backwards compatibility or direct lookup)
    if (ctype_digit($identifier)) {
        $user = self::find($identifier);
        if ($user) return $user;
    }

    // Lookup user by matching token
    $users = self::where('role', 'presentative_staff')
        ->whereNotNull('authorization_letter')
        ->get();

    foreach ($users as $user) {
        if (hash_equals($user->authorization_letter_token, $identifier)) {
            return $user;
        }
    }

    return null;
}








}
