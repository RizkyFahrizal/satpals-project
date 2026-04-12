<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandMOU extends Model
{
    use HasFactory;

    protected $table = 'band_mous';

    protected $fillable = [
        'band_id',
        'mou_document',
        'mou_description',
        'effective_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the band this MoU belongs to
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    /**
     * Check if MoU is still active
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->expiry_date === null || $this->expiry_date->isFuture());
    }
}
