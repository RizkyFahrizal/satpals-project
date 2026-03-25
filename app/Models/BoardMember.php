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
        'wakil_ketua_umum' => 'Wakil Ketua Umum',
        'sekretaris' => 'Sekretaris',
        'bendahara' => 'Bendahara',
        'mpa' => 'Ketua Majelis Perwakilan Anggota',
        'subsie_band' => 'Subsie Band',
        'subsie_peralatan' => 'Subsie Peralatan',
        'subsie_humas' => 'Subsie Humas',
        'subsie_pdd' => 'Subsie Produksi dan Dokumentasi',
        'subsie_kesekretariatan' => 'Subsie Kesekretariatan',
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
     * Create user account for this board member
     */
    public function createUserAccount(): User
    {
        $member = $this->member;
        
        // Generate email from npm
        $email = strtolower(str_replace(' ', '', $member->nama_lengkap)) . '@satpals.com';
        
        // Check if email exists, append number if needed
        $originalEmail = $email;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@satpals.com', $counter . '@satpals.com', $originalEmail);
            $counter++;
        }

        $user = User::create([
            'member_id' => $member->id,
            'name' => $member->nama_lengkap,
            'email' => $email,
            'password' => Hash::make('satpals123'), // Default password
            'role' => 'pengurus',
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
     * Get current periode (academic year format)
     */
    public static function getCurrentPeriode(): string
    {
        $year = now()->year;
        $month = now()->month;
        
        // If before July, use previous year
        if ($month < 7) {
            return ($year - 1) . '/' . $year;
        }
        
        return $year . '/' . ($year + 1);
    }
}
