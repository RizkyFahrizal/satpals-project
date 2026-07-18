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
        'harga_pokok',
        'diskon_persen',
        'diskon_nominal',
        'harga_final',
        'renter_notes',
        'status',
        'admin_notes',
        'income_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'total_price' => 'decimal:2',
        'harga_pokok' => 'integer',
        'diskon_persen' => 'integer',
        'diskon_nominal' => 'integer',
        'harga_final' => 'integer',
    ];

    /**
     * Boot method to auto-generate order_number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!empty($model->order_number)) {
                return;
            }

            $today = now();
            $dateFormat = $today->format('dmY');

            $latestCode = self::where('order_number', 'like', 'SA%' . $dateFormat)
                ->orderByDesc('order_number')
                ->value('order_number');

            $latestSequence = 0;
            if ($latestCode) {
                $sequencePart = substr($latestCode, 2, -8);
                $latestSequence = (int) $sequencePart;
            }

            $sequence = str_pad($latestSequence + 1, 2, '0', STR_PAD_LEFT);
            $model->order_number = 'SA' . $sequence . $dateFormat;
        });
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(EquipmentRentalRequestItem::class, 'equipment_rental_request_id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
