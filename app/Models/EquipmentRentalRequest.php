<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'renter_name',
        'renter_npm_nik',
        'renter_phone',
        'renter_email',
        'renter_ktp_ktm',
        'rental_location',
        'start_date',
        'end_date',
        'duration_days',
        'total_price',
        'renter_notes',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(EquipmentRentalRequestItem::class, 'equipment_rental_request_id');
    }
}
