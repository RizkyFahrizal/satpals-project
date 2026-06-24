<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $reason;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $reason = null)
    {
        $this->booking = $booking;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Permintaan Anda Ditolak';
        // Customize subject slightly if model has a code
        $code = $this->booking->booking_code ?? $this->booking->order_number ?? $this->booking->kode_order ?? null;
        if ($code) {
            $subject .= ' - ' . $code;
        }

        return $this->subject($subject)
                    ->view('emails.booking-rejected');
    }
}
