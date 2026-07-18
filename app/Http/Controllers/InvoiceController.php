<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRentalRequest;
use App\Models\StudioBooking;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Generate PDF invoice for a booking
     * GET /invoices/{bookingId}/download
     */
    public function download($id)
    {
        $booking = $this->resolveBooking($id);

        if ($booking instanceof StudioBooking) {
            $pdf = PDF::loadView('invoices.studio-booking', [
                'booking' => $booking,
            ]);

            return $pdf->download('Invoice-' . ($booking->booking_code ?? $booking->id) . '.pdf');
        }

        // Generate PDF from blade template
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $booking
        ]);

        // Return PDF for download
        return $pdf->download('Invoice-' . $booking->order_number . '.pdf');
    }

    /**
     * Display PDF invoice in browser
     * GET /invoices/{bookingId}/view
     */
    public function view($id)
    {
        $booking = $this->resolveBooking($id);

        if ($booking instanceof StudioBooking) {
            $pdf = PDF::loadView('invoices.studio-booking', [
                'booking' => $booking,
            ]);

            return $pdf->stream('Invoice-' . ($booking->booking_code ?? $booking->id) . '.pdf');
        }

        // Generate PDF from blade template
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $booking
        ]);

        // Return PDF to display in browser
        return $pdf->stream('Invoice-' . $booking->order_number . '.pdf');
    }

    /**
     * Get PDF content as string (for email attachment)
     */
    public function getContent($id)
    {
        $booking = $this->resolveBooking($id);

        if ($booking instanceof StudioBooking) {
            $pdf = PDF::loadView('invoices.studio-booking', [
                'booking' => $booking,
            ]);

            return $pdf->output();
        }

        // Generate PDF from blade template
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $booking
        ]);

        return $pdf->output();
    }

    private function resolveBooking($id)
    {
        $studioBooking = StudioBooking::find($id);

        if ($studioBooking) {
            return $studioBooking;
        }

        return EquipmentRentalRequest::with('items.equipment')->findOrFail($id);
    }
}
