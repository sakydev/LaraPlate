<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $username
 * @property string $status
 * @property int $level
 * @property string $email
 * @property string $email_verified_at;
 * @property string $password
 * @property string|null $remember_token
 *
 * @property Carbon|string $created_at
 * @property Carbon|string $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    public const ACTIVE_STATE = 'active';

    public const INACTIVE_STATE = 'inactive';

    public const DEFAULT_LEVEL = 5;

    public const ADMIN_LEVEL = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
        'level',
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

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function isAdmin(): bool
    {
        return $this->level === self::ADMIN_LEVEL;
    }

    public function isActive(): bool
    {
        return $this->status === self::ACTIVE_STATE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::INACTIVE_STATE;
    }
}
