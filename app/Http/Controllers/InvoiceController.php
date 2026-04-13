<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRentalRequest;
use PDF;

class InvoiceController extends Controller
{
    /**
     * Generate PDF invoice for a booking
     * GET /invoices/{bookingId}/download
     */
    public function download($id)
    {
        $booking = EquipmentRentalRequest::with('items.equipment')->findOrFail($id);

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
        $booking = EquipmentRentalRequest::with('items.equipment')->findOrFail($id);

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
        $booking = EquipmentRentalRequest::with('items.equipment')->findOrFail($id);

        // Generate PDF from blade template
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $booking
        ]);

        return $pdf->output();
    }
}
