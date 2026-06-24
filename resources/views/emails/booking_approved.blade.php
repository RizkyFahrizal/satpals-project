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
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->renter_name }}</strong>, permohonan peralatan Anda sudah disetujui.</p>
                        <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">Invoice PDF terlampir sebagai bukti transaksi.</p>
                        
                        <!-- Detail Pemohon dan Equipment -->
                        <div class="section detail-box">
                                <h2 style="margin:0 0 16px;font-size:18px;color:#1e40af;">📋 Detail Permohonan Peralatan</h2>
                                
                                <div style="margin-bottom:16px;">
                                        <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;font-weight:bold;">No. Order</p>
                                        <p style="margin:0 0 16px;font-size:15px;font-weight:bold;color:#1f2937;">{{ $booking->order_number }}</p>
                                </div>
                                
                                <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:16px;">
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Nama Pemohon</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->renter_name ?? '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">No. Telepon</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->renter_phone ?? '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Email</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->renter_email ?? '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">NPM/NIK</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->renter_npm_nik ?? '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Lokasi Pengambilan</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->rental_location ?? '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Tanggal Mulai</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->start_date ? $booking->start_date->translatedFormat('d F Y') : '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Tanggal Selesai</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->end_date ? $booking->end_date->translatedFormat('d F Y') : '-' }}</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Durasi</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->duration_days ?? '-' }} hari</td>
                                        </tr>
                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                <td class="label" style="color:#6b7280;">Catatan</td>
                                                <td style="color:#1f2937;font-weight:500;">{{ $booking->renter_notes ?? '-' }}</td>
                                        </tr>
                                </table>

                                <!-- Detail Peralatan yang Disewa -->
                                @if($booking->items && $booking->items->count() > 0)
                                <div style="margin-top:20px;padding-top:16px;border-top:2px solid #e5e7eb;">
                                        <p style="margin:0 0 12px;font-size:13px;color:#6b7280;text-transform:uppercase;font-weight:bold;">🔧 Daftar Peralatan</p>
                                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                                                <thead>
                                                        <tr style="background:#f3f4f6;border-bottom:2px solid #d1d5db;">
                                                                <th style="padding:10px;text-align:left;color:#374151;font-weight:bold;">Peralatan</th>
                                                                <th style="padding:10px;text-align:center;color:#374151;font-weight:bold;">Qty</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        @foreach($booking->items as $item)
                                                        <tr style="border-bottom:1px solid #e5e7eb;">
                                                                <td style="padding:10px;color:#1f2937;">{{ $item->equipment_name ?? $item->equipment->name ?? '-' }}</td>
                                                                <td style="padding:10px;text-align:center;color:#1f2937;">{{ $item->quantity ?? 1 }}</td>
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                        </table>
                                </div>
                                @endif

                                <!-- Detail Harga -->
                                <div style="margin-top:20px;padding-top:16px;border-top:2px solid #e5e7eb;">
                                        <p style="margin:0 0 12px;font-size:13px;color:#6b7280;text-transform:uppercase;font-weight:bold;">💵 Detail Harga</p>
                                        <table style="width:100%;border-collapse:collapse;font-size:14px;">
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;width:50%;">Harga Pokok</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;text-align:right;">Rp {{ number_format($booking->harga_pokok ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                @if($booking->diskon_persen > 0 || $booking->diskon_nominal > 0)
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Diskon @if($booking->diskon_persen > 0)({{ $booking->diskon_persen }}%)@endif</td>
                                                        <td style="padding:8px 0;color:#dc2626;font-weight:500;text-align:right;">- Rp {{ number_format($booking->diskon_nominal ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                @endif
                                                <tr style="background:#f9fafb;border-bottom:2px solid #3b82f6;">
                                                        <td style="padding:12px 0;color:#1e40af;font-weight:bold;">Harga Final</td>
                                                        <td style="padding:12px 0;color:#1e40af;font-weight:bold;text-align:right;font-size:16px;">Rp {{ number_format($booking->harga_final ?? 0, 0, ',', '.') }}</td>
                                                </tr>
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