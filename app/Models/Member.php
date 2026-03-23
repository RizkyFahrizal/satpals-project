<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    const STATUS_AKTIF = 'aktif';
    const STATUS_ALUMNI = 'alumni';

    const SPESIFIKASI_OPTIONS = [
        'drum' => 'Drum',
        'keyboard' => 'Keyboard',
        'vocal' => 'Vocal',
        'bass' => 'Bass',
        'guitar' => 'Guitar',
    ];

    protected $fillable = [
        'diklat_registration_id',
        'diklat_period_id',
        'nama_lengkap',
        'jenis_kelamin',
        'no_telepon',
        'npm',
        'fakultas',
        'prodi',
        'spesifikasi',
        'spesifikasi_lainnya',
        'tahun_daftar',
        'angkatan',
        'status',
        'foto',
    ];

    protected $casts = [
        'spesifikasi' => 'array',
        'spesifikasi_lainnya' => 'array',
        'tahun_daftar' => 'integer',
    ];

    /**
     * Relationship with DiklatRegistration
     */
    public function diklatRegistration()
    {
        return $this->belongsTo(DiklatRegistration::class);
    }

    /**
     * Relationship with DiklatPeriod
     */
    public function diklatPeriod()
    {
        return $this->belongsTo(DiklatPeriod::class);
    }

    /**
     * Relationship with BoardMember (kepengurusan)
     */
    public function boardPositions()
    {
        return $this->hasMany(BoardMember::class);
    }

    /**
     * Get active board position
     */
    public function activeBoardPosition()
    {
        return $this->hasOne(BoardMember::class)->where('is_active', true);
    }

    /**
     * Relationship with User (if this member has login account)
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Check if member is currently a board member
     */
    public function isPengurus(): bool
    {
        return $this->boardPositions()->where('is_active', true)->exists();
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_ALUMNI => 'Alumni',
            default => $this->status,
        };
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_AKTIF => 'green',
            self::STATUS_ALUMNI => 'blue',
            default => 'gray',
        };
    }

    /**
     * Create member from approved diklat registration
     */
    public static function createFromDiklatRegistration(DiklatRegistration $registration): self
    {
        return self::create([
            'diklat_registration_id' => $registration->id,
            'diklat_period_id' => $registration->diklat_period_id,
            'nama_lengkap' => $registration->nama_lengkap,
            'jenis_kelamin' => $registration->jenis_kelamin,
            'no_telepon' => $registration->no_telepon_pribadi,
            'npm' => $registration->npm,
            'fakultas' => $registration->fakultas,
            'prodi' => $registration->prodi,
            'spesifikasi' => $registration->spesifikasi,
            'spesifikasi_lainnya' => $registration->spesifikasi_lainnya,
            'tahun_daftar' => now()->year,
            'angkatan' => $registration->period?->tahun_masuk ?? now()->year,
            'status' => self::STATUS_AKTIF,
        ]);
    }

    /**
     * Get members who should be converted to alumni (4+ years from period)
     */
    public static function getExpiredMembers()
    {
        $fourYearsAgo = now()->subYears(4)->year;
        return self::where('status', self::STATUS_AKTIF)
            ->whereHas('diklatPeriod', function ($query) use ($fourYearsAgo) {
                $query->where('tahun_masuk', '<=', $fourYearsAgo);
            })
            ->get();
    }

    /**
     * Convert member status to alumni
     */
    public function convertToAlumni(): bool
    {
        $this->update(['status' => self::STATUS_ALUMNI]);
        return true;
    }
}
