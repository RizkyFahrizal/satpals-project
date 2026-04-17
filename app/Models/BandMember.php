<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'member_name',
        'role',
        'bio',
        'photo',
    ];

    /**
     * Get the band this member belongs to
     */
    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
