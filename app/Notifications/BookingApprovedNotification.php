<?php

namespace App\Notifications;

use App\Models\EquipmentRentalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(EquipmentRentalRequest $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan Disetujui - Invoice ' . $this->booking->order_number)
            ->greeting('Halo ' . $this->booking->renter_name . '!')
            ->line('Pesanan Anda telah disetujui oleh admin Satya Palapa.')
            ->line('Nomor Pesanan: **' . $this->booking->order_number . '**')
            ->line('Tanggal Penyewaan: ' . \Carbon\Carbon::parse($this->booking->start_date)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->booking->end_date)->format('d M Y'))
            ->line('Total Harga: **Rp ' . number_format($this->booking->total_price, 0, ',', '.') . '**')
            ->line('---')
            ->line('📋 Silakan unduh invoice di bawah ini:')
            ->action('Download Invoice PDF', route('invoice.download', $this->booking->id))
            ->line('---')
            ->line('📝 **Instruksi Pembayaran:**')
            ->line('1. Transfer ke rekening yang akan dikirim melalui email terpisah')
            ->line('2. Kirim bukti transfer ke WhatsApp admin')
            ->line('3. Lampirkan nomor pesanan ini: ' . $this->booking->order_number)
            ->line('4. Tunggu konfirmasi pembayaran dari admin')
            ->line('---')
            ->line('Jika ada pertanyaan, silakan hubungi admin melalui WhatsApp.')
            ->salutation('Terima kasih telah memilih Satya Palapa!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'order_number' => $this->booking->order_number,
        ];
    }
}
