<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Permohonan Peralatan Disetujui</title>
        <style>
                body { margin: 0; padding: 0; background: #f9fafb; font-family: Arial, sans-serif; color: #111827; }
                .container { max-width: 640px; margin: 0 auto; padding: 24px; }
                .header { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: #fff; padding: 28px; border-radius: 16px 16px 0 0; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { background: #fff; padding: 28px; border-radius: 0 0 16px 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
                .section { padding: 16px; border-radius: 12px; margin: 20px 0; }
                .detail-box { background: #eff6ff; border-left: 4px solid #3b82f6; }
                .payment-box { background: #dcfce7; border-left: 4px solid #22c55e; }
                table { width: 100%; border-collapse: collapse; font-size: 14px; }
                td { padding: 8px 0; }
                .label { color: #6b7280; width: 40%; }
                .payment-info { background: #fff; border: 2px solid #22c55e; padding: 12px; border-radius: 8px; text-align: center; margin-top: 12px; }
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
        @endphp
        
        <div class="container">
                <div class="header">
                        <h1>✅ Permohonan Disetujui</h1>
                        <p>Satya Palapa</p>
                </div>

                <div class="content">
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->nama_pemohon }}</strong>, permohonan peralatan Anda sudah disetujui.</p>
                        <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">Invoice PDF terlampir sebagai bukti transaksi.</p>
                        
                        <div class="section detail-box">
                                <h2 style="margin:0 0 12px;font-size:18px;color:#1e40af;">📋 Detail Permohonan</h2>
                                <table>
                                        <tr><td class="label">Kode Booking</td><td style="font-weight:bold;">{{ $booking->booking_code }}</td></tr>
                                        <tr><td class="label">Tanggal</td><td>{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : '-' }}</td></tr>
                                        <tr><td class="label">Durasi Rental</td><td>{{ $booking->durasi_rental ?? '-' }} hari</td></tr>
                                </table>
                        </div>

                        @if(($booking->harga_final ?? 0) > 0)
                                <div class="section payment-box">
                                        <h3 style="margin:0 0 12px;font-size:16px;color:#15803d;">💰 Informasi Pembayaran</h3>
                                        <p style="margin:0 0 10px;font-size:13px;color:#166534;">Silakan transfer ke rekening UKM:</p>
                                        <div class="payment-info">
                                                <p class="bank-label">Nama Bank</p>
                                                <p class="bank-value">{{ $bankName }}</p>
                                                <p class="bank-label">Nomor Rekening</p>
                                                <p class="bank-value">{{ $bankAccount }}</p>
                                                <p class="bank-label">Atas Nama</p>
                                                <p class="bank-value">{{ $bankAccountName }}</p>
                                        </div>
                                </div>
                        @endif

                        <p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
                </div>
        </div>
</body>
</html>