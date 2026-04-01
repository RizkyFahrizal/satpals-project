<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudioBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_booking',
        'sesi',
        'keperluan',
        'status',
        'catatan_admin',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'approved_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const SESI_TIMES = [
        1 => ['label' => 'Sesi 1', 'start' => '08:00', 'end' => '11:00'],
        2 => ['label' => 'Sesi 2', 'start' => '11:00', 'end' => '14:00'],
        3 => ['label' => 'Sesi 3', 'start' => '14:00', 'end' => '17:00'],
        4 => ['label' => 'Sesi 4', 'start' => '17:00', 'end' => '20:00'],
    ];

    /**
     * Relationship: Booking belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Booking approved by user (pengurus)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get sesi label
     */
    public function getSesiLabelAttribute()
    {
        return self::SESI_TIMES[$this->sesi]['label'] ?? 'Unknown';
    }

    /**
     * Get sesi time range
     */
    public function getSesiTimeAttribute()
    {
        return self::SESI_TIMES[$this->sesi]['start'] . ' - ' . self::SESI_TIMES[$this->sesi]['end'];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_REJECTED => 'badge-error',
            self::STATUS_COMPLETED => 'badge-info',
            self::STATUS_CANCELLED => 'badge-ghost',
            default => 'badge-neutral',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Get bookings for a specific date
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('tanggal_booking', $date);
    }

    /**
     * Scope: Get approved bookings
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Get pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Check if booking is approved
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if sesi is available on a specific date
     */
    public static function isSesiAvailable($tanggal, $sesi)
    {
        return !self::byDate($tanggal)
            ->where('sesi', $sesi)
            ->approved()
            ->exists();
    }

    /**
     * Get available sesi for a date
     */
    public static function getAvailableSesi($tanggal)
    {
        $booked = self::byDate($tanggal)
            ->approved()
            ->pluck('sesi')
            ->toArray();

        return array_filter([1, 2, 3, 4], function($sesi) use ($booked) {
            return !in_array($sesi, $booked);
        });
    }
}
