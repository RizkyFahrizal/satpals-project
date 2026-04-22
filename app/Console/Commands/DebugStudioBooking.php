<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudioBooking;
use Carbon\Carbon;

class DebugStudioBooking extends Command
{
    protected $signature = 'debug:studio-booking {date} {sesi}';
    protected $description = 'Debug studio booking availability';

    public function handle()
    {
        $date = $this->argument('date');
        $sesi = $this->argument('sesi');

        $this->info("Checking date: $date, sesi: $sesi");

        // Check isSesiAvailable method
        $isAvailable = StudioBooking::isSesiAvailable($date, $sesi);
        $this->info("isSesiAvailable result: " . ($isAvailable ? 'TRUE' : 'FALSE'));

        // Manual query
        $query = StudioBooking::byDate($date)
            ->where('sesi', $sesi)
            ->whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED]);

        $this->info("SQL: " . $query->toSql());
        $this->info("Bindings: " . json_encode($query->getBindings()));

        $bookings = $query->get();
        $this->info("Found bookings: " . count($bookings));

        foreach ($bookings as $booking) {
            $this->info("  - ID: {$booking->id}, tanggal: {$booking->tanggal_booking}, sesi: {$booking->sesi}, status: {$booking->status}");
        }

        // Check all bookings for date
        $this->info("\nAll bookings for date $date:");
        $allBookings = StudioBooking::byDate($date)
            ->whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED])
            ->get();

        foreach ($allBookings as $booking) {
            $this->info("  - Sesi {$booking->sesi}: {$booking->status}");
        }
    }
}
