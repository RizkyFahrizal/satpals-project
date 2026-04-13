<?php

namespace App\Mail;

use App\Models\EquipmentRentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PDF;

class BookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(EquipmentRentalRequest $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Disetujui - Invoice ' . $this->booking->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_approved',
            with: [
                'booking' => $this->booking,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF invoice
        $pdf = PDF::loadView('emails.invoice', [
            'booking' => $this->booking
        ]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Invoice-' . $this->booking->order_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
