<?php

namespace Database\Factories;

use App\Models\StudioBooking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudioBookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudioBooking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'tanggal_booking' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'sesi' => $this->faker->numberBetween(1, 4),
            'keperluan' => $this->faker->sentence(5),
            'status' => StudioBooking::STATUS_PENDING,
            'catatan_admin' => null,
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    /**
     * Indicate that the booking is approved.
     *
     * @return $this
     */
    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StudioBooking::STATUS_APPROVED,
                'approved_by' => User::factory(),
                'approved_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the booking is rejected.
     *
     * @return $this
     */
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StudioBooking::STATUS_REJECTED,
                'approved_by' => User::factory(),
                'approved_at' => now(),
                'catatan_admin' => $this->faker->sentence(3),
            ];
        });
    }

    /**
     * Indicate that the booking is completed.
     *
     * @return $this
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StudioBooking::STATUS_COMPLETED,
            ];
        });
    }

    /**
     * Indicate that the booking is cancelled.
     *
     * @return $this
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StudioBooking::STATUS_CANCELLED,
            ];
        });
    }
}
