<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_name',
        'description',
        'price_per_hour',
        'price_per_event',
        'photo',
        'is_available',
        'whatsapp_number',
        'instagram_username',
        'tiktok_username',
        'youtube_url',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price_per_hour' => 'decimal:2',
        'price_per_event' => 'decimal:2',
    ];

    /**
     * Get the members for the band
     */
    public function members()
    {
        return $this->hasMany(BandMember::class);
    }

    /**
     * Get the genres for the band
     */
    public function genres()
    {
        return $this->hasMany(BandGenre::class);
    }

    /**
     * Get the portfolios for the band
     */
    public function portfolios()
    {
        return $this->hasMany(BandPrototype::class);
    }

    /**
     * Get the MoU for the band
     */
    public function mou()
    {
        return $this->hasOne(BandMOU::class);
    }

    /**
     * Get the rental requests for the band
     */
    public function rentalRequests()
    {
        return $this->hasMany(BandRentalRequest::class);
    }
}
