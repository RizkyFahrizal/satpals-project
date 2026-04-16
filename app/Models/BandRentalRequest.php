<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandRentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'user_id',
        'renter_name',
        'renter_phone',
        'rental_purpose',
        'performance_date',
        'performance_start_time',
        'performance_end_time',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'performance_date' => 'datetime',
    ];

    /**
     * Get the band for this rental request
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * Get the user who made this request
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
