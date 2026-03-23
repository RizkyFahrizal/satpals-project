<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiklatRegistration extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const SPESIFIKASI_OPTIONS = [
        'drum' => 'Drum',
        'keyboard' => 'Keyboard',
        'vocal' => 'Vocal',
        'bass' => 'Bass',
        'guitar' => 'Guitar',
    ];

    protected $fillable = [
        'diklat_period_id',
        'nama_lengkap',
        'jenis_kelamin',
        'no_telepon_pribadi',
        'no_telepon_ortu',
        'npm',
        'fakultas',
        'prodi',
        'tahun_daftar',
        'tahun_masuk',
        'bukti_pembayaran',
        'riwayat_penyakit',
        'riwayat_alergi',
        'status',
    ];

    protected $casts = [
        'tahun_daftar' => 'integer',
        'tahun_masuk' => 'integer',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Diterima',
            self::STATUS_REJECTED => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    /**
     * Relationship: Registration belongs to a period
     */
    public function period()
    {
        return $this->belongsTo(DiklatPeriod::class, 'diklat_period_id');
    }

    /**
     * Get tahun masuk from the period
     */
    public function getTahunMasukFromPeriodAttribute(): ?int
    {
        return $this->period?->tahun_masuk;
    }
}
