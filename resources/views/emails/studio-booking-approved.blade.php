<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Studio Disetujui</title>
        <style>
                body { margin: 0; padding: 0; background: #f9fafb; font-family: Arial, sans-serif; color: #111827; }
                .container { max-width: 640px; margin: 0 auto; padding: 24px; }
                .header { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #fff; padding: 28px; border-radius: 16px 16px 0 0; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .header p { margin: 10px 0 0; font-size: 14px; opacity: 0.95; }
                .content { background: #fff; padding: 28px; border-radius: 0 0 16px 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
                .section { padding: 16px; border-radius: 12px; margin: 20px 0; }
                .detail-box { background: #fefce8; border-left: 4px solid #f59e0b; }
                .payment-box { background: #dcfce7; border-left: 4px solid #22c55e; }
                .confirm-box { background: #ecfdf5; border-left: 4px solid #10b981; }
                table { width: 100%; border-collapse: collapse; font-size: 14px; }
                td { padding: 8px 0; }
                .label { color: #6b7280; width: 40%; }
                .value { font-weight: bold; }
                .payment-info { background: #fff; border: 2px solid #22c55e; padding: 12px; border-radius: 8px; text-align: center; margin-top: 12px; }
                .payment-info p { margin: 0; padding: 4px 0; }
                .bank-label { font-size: 12px; color: #6b7280; }
                .bank-value { font-size: 16px; font-weight: bold; color: #15803d; margin: 8px 0; }
                .btn { display: inline-block; background: #10b981; color: #fff; text-decoration: none; padding: 12px 18px; border-radius: 10px; font-weight: bold; }
        </style>
</head>
<body>
        @php
                $bankName = env('UKM_BANK_NAME', 'CIMB Niaga');
                $bankAccount = env('UKM_BANK_ACCOUNT', '2141375549');
                $bankAccountName = env('UKM_BANK_ACCOUNT_NAME', 'ZAHLUL NOER LAILY');
                $paymentContact = env('CONTACT_CP_BAND', '+6282301964809');
                $adminNumber = preg_replace('/[^0-9]/', '', $paymentContact);
                $confirmText = rawurlencode('Halo admin, saya telah melakukan pembayaran booking studio ' . ($booking->booking_code ?? $booking->id) . '. Berikut bukti transfer saya.');
        @endphp
        
        <div class="container">
                <div class="header">
                        <h1>✅ Booking Studio Disetujui</h1>
                        <p>Satya Palapa</p>
                </div>

                <div class="content">
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->nama_pemohon }}</strong>, booking studio Anda sudah disetujui admin Satya Palapa.</p>
                        <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">Di email ini juga terlampir invoice PDF sebagai bukti transaksi dan rincian harga booking Anda.</p>
                        
                        <div class="section detail-box">
                                <h2 style="margin:0 0 12px;font-size:18px;color:#92400e;">📋 Detail Booking</h2>
                                <table>
                                        @php
                                                $jumlahNonUkmLabel = (($booking->jumlah_non_ukm ?? 0) > 0) ? ($booking->jumlah_non_ukm . ' orang') : 'UKM semua';
                                        @endphp
                                        <tr><td class="label">Kode Booking</td><td class="value">{{ $booking->booking_code }}</td></tr>
                                        <tr><td class="label">Tanggal</td><td>{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : '-' }}</td></tr>
                                        <tr><td class="label">Sesi</td><td>{{ $booking->sesiLabel }}</td></tr>
                                        <tr><td class="label">Keperluan</td><td>{{ $booking->keperluan }}</td></tr>
                                        <tr><td class="label">Jumlah Non-UKM</td><td>{{ $jumlahNonUkmLabel }}</td></tr>
                                        <tr><td class="label">Harga Awal</td><td>Rp {{ number_format($booking->harga_pokok, 0, ',', '.') }}</td></tr>
                                        <tr><td class="label">Diskon</td><td>Rp {{ number_format($booking->diskon_nominal, 0, ',', '.') }}</td></tr>
                                        <tr><td class="label" style="color:#166534;">Total Akhir</td><td class="value" style="color:#166534;">Rp {{ number_format($booking->harga_final, 0, ',', '.') }}</td></tr>
                                </table>
                        </div>

                        @if(($booking->harga_final ?? 0) > 0)
                                <div class="section payment-box">
                                        <h3 style="margin:0 0 12px;font-size:16px;color:#15803d;">💰 Informasi Pembayaran</h3>
                                        <p style="margin:0 0 10px;font-size:13px;color:#166534;line-height:1.6;">Silakan transfer ke rekening UKM berikut:</p>
                                        <div class="payment-info">
                                                <p class="bank-label">Nama Bank</p>
                                                <p class="bank-value">{{ $bankName }}</p>
                                                <p class="bank-label">Nomor Rekening</p>
                                                <p class="bank-value" style="letter-spacing:1px;">{{ $bankAccount }}</p>
                                                <p class="bank-label">Atas Nama</p>
                                                <p class="bank-value">{{ $bankAccountName }}</p>
                                        </div>
                                </div>
                        @endif

                        <div class="section confirm-box">
                                <p style="margin:0 0 10px;font-size:14px;color:#065f46;line-height:1.6;">Setelah transfer, silakan kirim bukti pembayaran ke CP Peralatan melalui WhatsApp di bawah ini:</p>
                                <a href="https://wa.me/{{ $adminNumber }}?text={{ $confirmText }}" class="btn">💬 Kirim Bukti Pembayaran</a>
                                <p style="margin:10px 0 0;font-size:12px;color:#047857;">Kontak CP Peralatan: {{ $paymentContact }}</p>
                        </div>

                        <p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
                </div>
        </div>
</body>
</html>