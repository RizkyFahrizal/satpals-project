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
        if ($rental->performance_duration_hours === null && $rental->performance_duration_minutes === null) {
            return 0;
        }

        $totalMinutes = ($rental->performance_duration_hours * 60) + $rental->performance_duration_minutes;
        return ceil($totalMinutes / 60); // Round up to next hour
    }
}
