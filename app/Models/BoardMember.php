<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class BoardMember extends Model
{
    use HasFactory;

    const JABATAN_OPTIONS = [
        'ketua_umum' => 'Ketua Umum',
        'wakil_ketua_umum' => 'Wakil Ketua',
        'sekretaris' => 'Sekretaris',
        'bendahara' => 'Bendahara',
        'mpa' => 'MPA',
        'subsie_band' => 'Band',
        'subsie_peralatan' => 'Peralatan',
        'subsie_humas' => 'Humas',
        'subsie_pdd' => 'PDD',
        'subsie_kesekretariatan' => 'Kesekretariatan',
    ];

    // Grouping for display
    const JABATAN_PIMPINAN = ['ketua_umum', 'wakil_ketua_umum', 'sekretaris', 'bendahara', 'mpa'];
    const JABATAN_SUBSIE = ['subsie_band', 'subsie_peralatan', 'subsie_humas', 'subsie_pdd', 'subsie_kesekretariatan'];

    protected $fillable = [
        'member_id',
        'user_id',
        'diklat_period_id',
        'jabatan',
        'periode',
        'tanggal_buka',
        'tanggal_tutup',
        'is_active',
        'urutan',
        'foto',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'tanggal_buka' => 'datetime',
        'tanggal_tutup' => 'datetime',
    ];

    /**
     * Relationship with Member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relationship with DiklatPeriod
     */
    public function diklatPeriod()
    {
        return $this->belongsTo(DiklatPeriod::class);
    }

    /**
     * Relationship with User (login account)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get jabatan label
     */
    public function getJabatanLabelAttribute(): string
    {
        return self::JABATAN_OPTIONS[$this->jabatan] ?? $this->jabatan;
    }

    /**
     * Map jabatan to user role
     */
    public static function jabatanToRole($jabatan): string
    {
        return match($jabatan) {
            'ketua_umum' => 'ketua_umum',
            'wakil_ketua_umum' => 'wakil_ketua_umum',
            'sekretaris' => 'sekretaris',
            'bendahara' => 'bendahara',
            'mpa' => 'mpa',
            'subsie_band' => 'band',
            'subsie_peralatan' => 'peralatan',
            'subsie_humas' => 'humas',
            'subsie_pdd' => 'pdd',
            'subsie_kesekretariatan' => 'kesekretariatan',
            default => 'pengurus', // Fallback for legacy
        };
    }

    /**
     * Create user account for this board member
     */
    public function createUserAccount(): User
    {
        $member = $this->member;

        $existingUser = User::where('member_id', $member->id)->first();
        if ($existingUser) {
            $this->update(['user_id' => $existingUser->id]);
            return $existingUser;
        }
        
        // Generate email from member nama with gmail domain
        $email = strtolower(str_replace(' ', '', $member->nama_lengkap)) . '@gmail.com';
        
        // Check if email exists, append number if needed
        $originalEmail = $email;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@gmail.com', $counter . '@gmail.com', $originalEmail);
            $counter++;
        }

        // Generate default password from nama_lengkap (username)
        $defaultPassword = strtolower(str_replace(' ', '', $member->nama_lengkap));

        // Map jabatan to role
        $role = self::jabatanToRole($this->jabatan);

        $user = User::create([
            'member_id' => $member->id,
            'name' => $member->nama_lengkap,
            'email' => $email,
            'password' => Hash::make($defaultPassword), // Default password = username
            'role' => $role, // Use role mapped from jabatan
        ]);

        // Update this board member with user_id
        $this->update(['user_id' => $user->id]);

        return $user;
    }

    /**
     * Scope for active board members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific period
     */
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    /**
     * Get all available periodes from active members angkatan
     * Periode format: "tahun+1 / tahun+2" relative to angkatan
     */
    public static function getAvailablePeriodes()
    {
        // Get distinct angkatan from active members
        $angkatanList = Member::where('status', 'aktif')
            ->distinct()
            ->pluck('angkatan')
            ->filter()
            ->map(fn($tahun) => (int)$tahun)
            ->sort()
            ->reverse()
            ->values();
        
        // Convert angkatan to periode format: "tahun+1 / tahun+2"
        // Contoh: angkatan 2024 -> periode 2025/2026
        $periodeList = $angkatanList->map(function($tahun) {
            $tahun1 = $tahun + 1;
            $tahun2 = $tahun + 2;
            return "{$tahun1}/{$tahun2}";
        })->unique()->values();

        return $periodeList;
    }

    /**
     * Get current periode (academic year format: tahun / tahun+1)
     * Format: tahun+1 / tahun+2 relative to angkatan
     */
    public static function getCurrentPeriode(): string
    {
        $year = now()->year;
        $month = now()->month;
        
        // If before July, still in previous academic year
        if ($month < 7) {
            return ($year - 1) . '/' . $year;
        }
        
        // After July, in current academic year
        return $year . '/' . ($year + 1);
    }
}
