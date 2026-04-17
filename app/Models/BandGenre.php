<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandGenre extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'genre_name',
    ];

    /**
     * Get the band this genre belongs to
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
