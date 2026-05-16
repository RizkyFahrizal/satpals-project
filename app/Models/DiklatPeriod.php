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
        $this->save();
    }

    /**
     * Sync open/close status based on the selected date range.
     */
    public function syncStatusFromDates(): bool
    {
        if (!$this->tanggal_buka || !$this->tanggal_tutup) {
            return false;
        }

        $shouldBeOpen = now()->betweenIncluded(
            $this->tanggal_buka->copy()->startOfDay(),
            $this->tanggal_tutup->copy()->endOfDay()
        );

        if ($this->is_open === $shouldBeOpen) {
            return false;
        }

        $this->forceFill(['is_open' => $shouldBeOpen])->saveQuietly();

        return true;
    }

    /**
     * Sync all periods using their date windows.
     */
    public static function syncAllStatusesFromDates(): int
    {
        $updated = 0;

        static::whereNotNull('tanggal_buka')
            ->whereNotNull('tanggal_tutup')
            ->chunkById(100, function ($periods) use (&$updated) {
                foreach ($periods as $period) {
                    if ($period->syncStatusFromDates()) {
                        $updated++;
                    }
                }
            });

        return $updated;
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
