<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRentalUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_rental_id',
        'unit_name',
        'quantity',
        'description',
    ];

    // Relationships
    public function equipment()
    {
        return $this->belongsTo(EquipmentRental::class, 'equipment_rental_id');
    }
}
