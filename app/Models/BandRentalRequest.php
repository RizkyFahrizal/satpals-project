<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandRentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'renter_name',
        'renter_phone',
        'renter_email',
        'rental_purpose',
        'rental_type',
        'venue_address',
        'performance_date',
        'performance_start_time',
        'performance_end_time',
        'performance_duration_hours',
        'performance_duration_minutes',
        'break_duration_hours',
        'break_duration_minutes',
        'status',
        'admin_notes',
        'harga_pokok',
        'diskon_persen',
        'diskon_nominal',
        'harga_final',
        'kode_order',
        'income_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'rental_type' => 'string',
        'performance_date' => 'datetime',
    ];

    /**
     * Boot method to auto-generate kode_order
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate kode_order format: SB{urutan}{ddmmyy}
            $today = now();
            $dateFormat = $today->format('dmy');

            $latestCode = self::whereDate('created_at', $today->toDateString())
                ->where('kode_order', 'like', 'SB%' . $dateFormat)
                ->orderByDesc('kode_order')
                ->value('kode_order');

            $latestSequence = $latestCode ? (int) substr($latestCode, 2, 2) : 0;
            $urutan = str_pad($latestSequence + 1, 2, '0', STR_PAD_LEFT);
            
            // Generate kode_order
            $model->kode_order = 'SB' . $urutan . $dateFormat;
        });
    }

    /**
     * Get the band for this rental request
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
