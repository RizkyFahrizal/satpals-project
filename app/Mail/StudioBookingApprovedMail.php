<?php

namespace App\Mail;

use App\Models\StudioBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PDF;

class StudioBookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public StudioBooking $booking;

    public function __construct(StudioBooking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Studio Disetujui - ' . $this->booking->booking_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.studio-booking-approved',
            with: [
                'booking' => $this->booking,
                'adminWhatsApp' => config('contact.cp_peralatan', env('CONTACT_CP_PERALATAN')),
            ],
        );
    }

    /**
     * Get attachments for the message.
     */
    public function attachments(): array
    {
        $pdf = PDF::loadView('invoices.studio-booking', [
            'booking' => $this->booking,
        ]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Invoice-' . ($this->booking->booking_code ?? $this->booking->id) . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
