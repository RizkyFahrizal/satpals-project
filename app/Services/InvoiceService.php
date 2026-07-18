<?php

namespace App\Services;

use App\Models\BandRentalRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    /**
     * Generate invoice PDF for band rental
     */
    public static function generate(BandRentalRequest $rental)
    {
        $data = [
            'rental' => $rental,
            'band' => $rental->band,
            'user' => $rental->user,
            'invoiceDate' => now()->format('d M Y'),
            'invoiceNumber' => $rental->kode_order,
            'rentalType' => $rental->rental_type ?? 'hourly',
            'pricePerHour' => $rental->band?->price_per_hour ?? 0,
            'pricePerEvent' => $rental->band?->price_per_event ?? 0,
            'durationHours' => self::calculateDuration($rental),
        ];

        $pdf = Pdf::loadView('invoices.invoice', $data)
            ->setPaper('A4')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return $pdf;
    }

    /**
     * Download invoice PDF
     */
    public static function download(BandRentalRequest $rental)
    {
        return self::generate($rental)->download('Invoice_' . $rental->kode_order . '.pdf');
    }

    /**
     * Calculate duration in hours (rounded up)
     */
    private static function calculateDuration(BandRentalRequest $rental)
    {
        if (($rental->performance_duration_hours !== null || $rental->performance_duration_minutes !== null)
            && ((int) $rental->performance_duration_hours > 0 || (int) $rental->performance_duration_minutes > 0)) {
            $totalMinutes = ((int) $rental->performance_duration_hours * 60) + (int) $rental->performance_duration_minutes;
            return ceil($totalMinutes / 60);
        }

        if ($rental->performance_start_time && $rental->performance_end_time) {
            try {
                $startTime = \Carbon\Carbon::parse($rental->performance_start_time);
                $endTime = \Carbon\Carbon::parse($rental->performance_end_time);
                $totalMinutes = $startTime->diffInMinutes($endTime, false);

                if ($totalMinutes < 0) {
                    $totalMinutes += 24 * 60;
                }

                $breakMinutes = ((int) $rental->break_duration_hours * 60) + (int) $rental->break_duration_minutes;
                $mainMinutes = max(0, $totalMinutes - $breakMinutes);

                return ceil($mainMinutes / 60);
            } catch (\Throwable $exception) {
                return 0;
            }
        }

        return 0;
    }
}
