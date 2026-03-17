<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Role constants
     */
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_PENGURUS = 'pengurus';
    const ROLE_PUBLIC = 'public';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'password',
        'role',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is pengurus
     */
    public function isPengurus(): bool
    {
        return $this->role === self::ROLE_PENGURUS;
    }

    /**
     * Check if user is public
     */
    public function isPublic(): bool
    {
        return $this->role === self::ROLE_PUBLIC;
    }

    /**
     * Check if user has admin access (super_admin or pengurus)
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_PENGURUS]);
    }

    /**
     * Get role label for display
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_PENGURUS => 'Pengurus',
            self::ROLE_PUBLIC => 'Public',
            default => 'Unknown',
        };
    }

    /**
     * Relationship with Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relationship with BoardMember
     */
    public function boardMember()
    {
        return $this->hasOne(BoardMember::class);
    }
}
