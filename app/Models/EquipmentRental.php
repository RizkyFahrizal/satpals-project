<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'photo',
        'notes',
        'price_per_day',
        'operator_crew_price',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price_per_day' => 'decimal:2',
        'operator_crew_price' => 'decimal:2',
    ];

    // Relationships
    public function units()
    {
        return $this->hasMany(EquipmentRentalUnit::class, 'equipment_rental_id');
    }

    public function requestItems()
    {
        return $this->hasMany(EquipmentRentalRequestItem::class, 'equipment_rental_id');
    }
}
