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
    const ROLE_PUBLIC = 'public';
    
    // Specific board member roles
    const ROLE_KETUA_UMUM = 'ketua_umum';
    const ROLE_WAKIL_KETUA_UMUM = 'wakil_ketua_umum';
    const ROLE_BENDAHARA = 'bendahara';
    const ROLE_SEKRETARIS = 'sekretaris';
    const ROLE_MPA = 'mpa';
    const ROLE_BAND = 'band';
    const ROLE_PERALATAN = 'peralatan';
    const ROLE_HUMAS = 'humas';
    const ROLE_PDD = 'pdd';
    const ROLE_KESEKRETARIATAN = 'kesekretariatan';
    
    // Deprecated role (for backward compatibility)
    const ROLE_PENGURUS = 'pengurus';
    
    /**
     * Get all board member role options
     */
    public static function getBoardMemberRoles(): array
    {
        return [
            self::ROLE_KETUA_UMUM => 'Ketua Umum',
            self::ROLE_WAKIL_KETUA_UMUM => 'Wakil Ketua Umum',
            self::ROLE_BENDAHARA => 'Bendahara',
            self::ROLE_SEKRETARIS => 'Sekretaris',
            self::ROLE_MPA => 'Majelis Perwakilan Anggota',
            self::ROLE_BAND => 'Subsie Band',
            self::ROLE_PERALATAN => 'Subsie Peralatan',
            self::ROLE_HUMAS => 'Subsie Humas',
            self::ROLE_PDD => 'Subsie PDD',
            self::ROLE_KESEKRETARIATAN => 'Subsie Kesekretariatan',
        ];
    }
    
    /**
     * Get all role labels (board member roles + super_admin + public)
     */
    public static function getRoleLabels(): array
    {
        return array_merge(
            self::getBoardMemberRoles(),
            [
                self::ROLE_SUPER_ADMIN => 'Super Admin',
                self::ROLE_PUBLIC => 'Public',
            ]
        );
    }
    
    /**
     * Get board member roles excluding ketua_umum (for struktur pengurus form)
     */
    public static function getBoardMemberRolesWithoutKetuaUmum(): array
    {
        $roles = self::getBoardMemberRoles();
        unset($roles[self::ROLE_KETUA_UMUM]);
        return $roles;
    }

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
        'is_active',
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
        'is_active' => 'boolean',
    ];

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if user is pengurus (any specific board role)
     */
    public function isPengurus(): bool
    {
        return $this->isBoardMember();
    }
    
    /**
     * Check if user is a board member (any specific role)
     */
    public function isBoardMember(): bool
    {
        return in_array($this->role, array_keys(self::getBoardMemberRoles()));
    }

    /**
     * Check if user is public
     */
    public function isPublic(): bool
    {
        return $this->role === self::ROLE_PUBLIC;
    }

    /**
     * Check if user has admin access.
     * For this app, admin login is allowed when the user account is active.
     */
    public function hasAdminAccess(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    /**
     * Check if user can add board members (pengurus)
     * Only super_admin, ketua_umum, wakil_ketua_umum, and mpa (active in current periode) can add
     */
    public function canAddBoardMembers($selectedPeriode = null): bool
    {
        // Super admin can always add
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true;
        }

        // Only specific roles can add pengurus
        $allowedRoles = [
            self::ROLE_KETUA_UMUM,
            self::ROLE_WAKIL_KETUA_UMUM,
            self::ROLE_MPA,
        ];

        if (!in_array($this->role, $allowedRoles)) {
            return false;
        }

        // Only active user accounts can add pengurus; role/linkage is not used here.
        return (bool) ($this->is_active ?? true);
    }

    /**
     * Get role label for display
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_PUBLIC => 'Public',
            self::ROLE_KETUA_UMUM => 'Ketua Umum',
            self::ROLE_WAKIL_KETUA_UMUM => 'Wakil Ketua',
            self::ROLE_BENDAHARA => 'Bendahara',
            self::ROLE_SEKRETARIS => 'Sekretaris',
            self::ROLE_MPA => 'MPA',
            self::ROLE_BAND => 'Band',
            self::ROLE_PERALATAN => 'Peralatan',
            self::ROLE_HUMAS => 'Humas',
            self::ROLE_PDD => 'PDD',
            self::ROLE_KESEKRETARIATAN => 'Kesekretariatan',
            self::ROLE_PENGURUS => 'Pengurus', // Legacy
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
