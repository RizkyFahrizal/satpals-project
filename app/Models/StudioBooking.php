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
        'booking_code',
        'tanggal_booking',
        'sesi',
        'keperluan',
        'renter_email',
        'renter_phone',
        'jumlah_non_ukm',
        'harga_satuan',
        'harga_pokok',
        'diskon_persen',
        'diskon_nominal',
        'harga_final',
        'status',
        'catatan_admin',
        'approved_by',
        'approved_at',
        'income_id',
        'nomor_identitas',
        'nama_pemohon',
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
     * Relationship: linked income record
     */
    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    /**
     * Boot model and generate booking code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->booking_code) {
                $today = now();
                $todaySuffix = $today->format('dmY');
                $latestCode = self::whereDate('created_at', $today->toDateString())
                    ->where('booking_code', 'like', 'SS%' . $todaySuffix)
                    ->orderByDesc('booking_code')
                    ->value('booking_code');

                $latestSequence = $latestCode ? (int) substr($latestCode, 2, 2) : 0;
                $sequence = str_pad($latestSequence + 1, 2, '0', STR_PAD_LEFT);
                $model->booking_code = 'SS' . $sequence . $today->format('dmY');
            }
        });
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
            self::STATUS_CANCELLED => 'badge-error',
            default => 'badge-neutral',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Get bookings for a specific date
     */
    public function scopeByDate($query, $date)
    {
        // Convert to Carbon instance if string
        if (is_string($date)) {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        }
        
        return $query->whereDate('tanggal_booking', $date);
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
     * Check if booking is currently blocking the slot
     */
    public function isBlockingSlot()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true);
    }

    /**
     * Check if sesi is available on a specific date
     */
    public static function isSesiAvailable($tanggal, $sesi)
    {
        return !self::byDate($tanggal)
            ->where('sesi', $sesi)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->exists();
    }

    /**
     * Get available sesi for a date
     */
    public static function getAvailableSesi($tanggal)
    {
        $booked = self::byDate($tanggal)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->pluck('sesi')
            ->toArray();

        return array_filter([1, 2, 3, 4], function($sesi) use ($booked) {
            return !in_array($sesi, $booked);
        });
    }
}
