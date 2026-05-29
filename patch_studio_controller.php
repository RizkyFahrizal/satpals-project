<?php

$file = 'app/Http/Controllers/Admin/StudioBookingController.php';
$content = file_get_contents($file);

// Find and replace - using simple markers instead of complex regex
$search = <<<'CODE'
        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->queue(new StudioBookingApprovedMail($booking->fresh()));
CODE;

$replace = <<<'CODE'
        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->queue(new StudioBookingApprovedMail($booking->fresh()));
                Log::info('Email approval studio booking queued', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'recipient_email' => $recipientEmail,
                ]);
                
                return back()->with('success', ($hargaFinal > 0
                    ? 'Booking berhasil di-approve dan pemasukan telah dibuat'
                    : 'Booking berhasil di-approve tanpa pemasukan karena booking UKM semua')
                    . "\n✓ Email notifikasi sedang diproses dan akan dikirim ke {$recipientEmail}");
CODE;

if (strpos($content, $search) !== false) {
    $content = str_replace($search, $replace, $content);
    
    // Also remove the old error handling and separate return statement
    $old_catch = <<<'CATCH'
                                                                         } catch (\Throwable $exception) {
                Log::warning('Gagal mengirim email approval booking studio', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'recipient_email' => $recipientEmail,
                    'error' => $exception->getMessage(),
                ]);

                return back()->with('success', $hargaFinal > 0
                    ? 'Booking berhasil di-approve dan pemasukan telah dibuat'
                    : 'Booking berhasil di-approve tanpa pemasukan karena booking UKM semua')
                                                                                       ->with('warning', 'Booking sudah disetujui, tetapi email notifikasi gagal dikirim.');
                                                                   }
        }

        return back()->with('success', $hargaFinal > 0
            ? 'Booking berhasil di-approve dan pemasukan telah dibuat'
            : 'Booking berhasil di-approve tanpa pemasukan karena booking UKM semua');
CATCH;

    $new_catch = <<<'CATCH'
            } catch (\Throwable $exception) {
                Log::error('Gagal queue email approval booking studio', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'recipient_email' => $recipientEmail,
                    'error' => $exception->getMessage(),
                ]);

                return back()->with('success', ($hargaFinal > 0
                    ? 'Booking berhasil di-approve dan pemasukan telah dibuat'
                    : 'Booking berhasil di-approve tanpa pemasukan karena booking UKM semua')
                    . "\n⚠ Gagal mengirim email ke {$recipientEmail}: {$exception->getMessage()}");
            }
        }
CATCH;
    
    $content = str_replace($old_catch, $new_catch, $content);
    
    file_put_contents($file, $content);
    echo "✓ Updated StudioBookingController successfully\n";
} else {
    echo "✗ Could not find the pattern to replace\n";
}
