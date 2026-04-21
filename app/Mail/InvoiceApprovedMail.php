<?php

namespace App\Mail;

use App\Models\BandRentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public BandRentalRequest $rental;
    public string $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(BandRentalRequest $rental, string $pdfPath)
    {
        $this->rental = $rental;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Sewa Band Disetujui - Invoice ' . $this->rental->kode_order,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-approved',
            with: [
                'rental' => $this->rental,
                'adminWhatsApp' => env('CONTACT_CP_BAND'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Invoice_' . $this->rental->kode_order . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
