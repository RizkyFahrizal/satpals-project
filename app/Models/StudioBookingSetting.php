<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class StudioBookingSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_per_person',
    ];

    public static function currentPricePerPerson(): int
    {
        if (!Schema::hasTable('studio_booking_settings')) {
            return 15000;
        }

        $setting = static::query()->first();

        return (int) ($setting?->price_per_person ?? 15000);
    }

    public static function updatePricePerPerson(int $pricePerPerson): self
    {
        if (!Schema::hasTable('studio_booking_settings')) {
            return new self([
                'price_per_person' => $pricePerPerson,
            ]);
        }

        $setting = static::query()->first();

        if (!$setting) {
            return static::create([
                'price_per_person' => $pricePerPerson,
            ]);
        }

        $setting->update([
            'price_per_person' => $pricePerPerson,
        ]);

        return $setting;
    }
}
