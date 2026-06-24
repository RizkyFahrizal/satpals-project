<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice Disetujui</title>
        <style>
                body { margin: 0; padding: 0; background: #f9fafb; font-family: Arial, sans-serif; color: #111827; }
                .container { max-width: 640px; margin: 0 auto; padding: 24px; }
                .header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: #fff; padding: 28px; border-radius: 16px 16px 0 0; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { background: #fff; padding: 28px; border-radius: 0 0 16px 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
                .section { padding: 16px; border-radius: 12px; margin: 20px 0; }
                .payment-box { background: #dcfce7; border-left: 4px solid #22c55e; }
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
                        <h1>✅ Invoice Disetujui</h1>
                        <p>Satya Palapa</p>
                </div>

                <div class="content">
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo, invoice Anda sudah disetujui.</p>
                        <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">PDF invoice terlampir untuk referensi Anda.</p>
                        
                        <!-- Detail Peminta dan Band -->
                        <div class="section" style="background: #f3f4f6; border-left: 4px solid #8b5cf6;">
                                <h3 style="margin:0 0 16px;font-size:16px;color:#7c3aed;">📋 Detail Sewa Band</h3>
                                
                                <div style="margin-bottom:16px;">
                                        <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;font-weight:bold;">Kode Order</p>
                                        <p style="margin:0 0 16px;font-size:15px;font-weight:bold;color:#1f2937;">{{ $rental->kode_order }}</p>
                                </div>
                                
                                <div style="margin-bottom:16px;">
                                        <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;font-weight:bold;">👤 Data Peminta</p>
                                        <table style="width:100%;border-collapse:collapse;font-size:14px;">
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;width:40%;">Nama</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->renter_name ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">No. Telepon</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->renter_phone ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Email</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->renter_email ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Tujuan</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->rental_purpose ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Lokasi Venue</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->venue_address ?? '-' }}</td>
                                                </tr>
                                        </table>
                                </div>

                                <div style="margin-bottom:16px;">
                                        <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;font-weight:bold;">🎸 Data Band</p>
                                        <table style="width:100%;border-collapse:collapse;font-size:14px;">
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;width:40%;">Nama Band</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->band->name ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Jenis Rental</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ ucfirst($rental->rental_type ?? '-') }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Tanggal Perform</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->performance_date ? $rental->performance_date->format('d M Y') : '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Jam Perform</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->performance_start_time ?? '-' }} - {{ $rental->performance_end_time ?? '-' }}</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Durasi</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->performance_duration_hours ?? 0 }} jam {{ $rental->performance_duration_minutes ?? 0 }} menit</td>
                                                </tr>
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Durasi Break</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;">{{ $rental->break_duration_hours ?? 0 }} jam {{ $rental->break_duration_minutes ?? 0 }} menit</td>
                                                </tr>
                                        </table>
                                </div>

                                <div>
                                        <p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;font-weight:bold;">💵 Detail Harga</p>
                                        <table style="width:100%;border-collapse:collapse;font-size:14px;">
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Harga Pokok</td>
                                                        <td style="padding:8px 0;color:#1f2937;font-weight:500;text-align:right;">Rp {{ number_format($rental->harga_pokok ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                @if($rental->diskon_persen > 0 || $rental->diskon_nominal > 0)
                                                <tr style="border-bottom:1px solid #e5e7eb;">
                                                        <td style="padding:8px 0;color:#6b7280;">Diskon @if($rental->diskon_persen > 0) ({{ $rental->diskon_persen }}%)@endif</td>
                                                        <td style="padding:8px 0;color:#dc2626;font-weight:500;text-align:right;">- Rp {{ number_format($rental->diskon_nominal ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                @endif
                                                <tr style="background:#f9fafb;border-bottom:2px solid #8b5cf6;">
                                                        <td style="padding:12px 0;color:#7c3aed;font-weight:bold;">Harga Final</td>
                                                        <td style="padding:12px 0;color:#7c3aed;font-weight:bold;text-align:right;font-size:16px;">Rp {{ number_format($rental->harga_final ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                        </table>
                                </div>
                        </div>
                        
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

                        <p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
                </div>
        </div>
</body>
</html>