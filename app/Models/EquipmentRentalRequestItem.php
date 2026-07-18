<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRentalRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_rental_request_id',
        'equipment_rental_id',
        'quantity',
        'price_per_day',
        'subtotal',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function request()
    {
        return $this->belongsTo(EquipmentRentalRequest::class, 'equipment_rental_request_id');
    }

    public function equipment()
    {
        return $this->belongsTo(EquipmentRental::class, 'equipment_rental_id');
    }
}
