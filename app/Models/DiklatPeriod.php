<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiklatPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_periode',
        'tahun_masuk',
        'rekening_number',
        'rekening_info',
        'is_open',
        'tanggal_buka',
        'tanggal_tutup',
        'keterangan',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
    ];

    /**
     * Relationship: A period has many diklat registrations
     */
    public function registrations()
    {
        return $this->hasMany(DiklatRegistration::class);
    }

    /**
     * Toggle period status (open/close)
     */
    public function toggleOpen(): void
    {
        $this->is_open = !$this->is_open;
        if ($this->is_open) {
            $this->tanggal_buka = now();
        } else {
            $this->tanggal_tutup = now();
        }
        $this->save();
    }

    /**
     * Get short display name for filters (e.g., "Angkatan 2023")
     */
    public function getShortNameAttribute(): string
    {
        return "Angkatan {$this->tahun_masuk}";
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_open ? 'Dibuka' : 'Ditutup';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_open ? 'badge-success' : 'badge-error';
    }

    /**
     * Accept all pending registrations in this period
     */
    public function acceptAllRegistrations(): int
    {
        return $this->registrations()
            ->where('status', 'pending')
            ->update(['status' => 'approved']);
    }
}
