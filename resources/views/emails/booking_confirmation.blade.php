<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .order-box {
            background: #f0f4ff;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
            border-radius: 4px;
        }
        .order-number {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }
        .info-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-table .label {
            font-weight: bold;
            width: 40%;
            color: #555;
        }
        .info-table .value {
            color: #333;
        }
        .next-steps {
            background: #fffbea;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #ff9800;
        }
        .next-steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
            color: #555;
        }
        .footer {
            background: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-radius: 4px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .highlight {
            background: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Pesanan Berhasil Dibuat!</h1>
            <p>Terima kasih telah mempercayai Satya Palapa Rent</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo <strong>{{ $booking->renter_name }}</strong>,</p>

            <p>Pesanan Anda telah berhasil dibuat dan sedang menunggu verifikasi dari admin kami. Berikut adalah detail pesanan Anda:</p>

            <!-- Order Info -->
            <div class="order-box">
                <div class="order-number">{{ $booking->order_number }}</div>
                <p style="margin: 5px 0; color: #666; font-size: 13px;">Nomor Pesanan Anda (simpan untuk referensi)</p>
            </div>

            <!-- Details Table -->
            <table class="info-table">
                <tr>
                    <td class="label">Tanggal Mulai</td>
                    <td class="value">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Selesai</td>
                    <td class="value">{{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Total Harga</td>
                    <td class="value">
                        <strong style="font-size: 16px; color: #27ae60;">
                            Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </table>

            <!-- Order Items -->
            @if($booking->items && $booking->items->count() > 0)
            <div style="margin: 12px 0;">
                <h4 style="margin:6px 0;color:#555;">Daftar Peralatan</h4>
                <table style="width:100%;border-collapse:collapse;font-size:14px;">
                    <thead>
                        <tr style="background:#f3f4f6;border-bottom:2px solid #e5e7eb;">
                            <th style="text-align:left;padding:8px;color:#374151;">Peralatan</th>
                            <th style="text-align:center;padding:8px;color:#374151;">Qty</th>
                            <th style="text-align:right;padding:8px;color:#374151;">Harga/Hari</th>
                            <th style="text-align:right;padding:8px;color:#374151;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->items as $item)
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:8px;">{{ $item->equipment->name ?? ($item->equipment_name ?? '-') }}</td>
                            <td style="padding:8px;text-align:center;">{{ $item->quantity ?? 1 }}</td>
                            <td style="padding:8px;text-align:right;">Rp {{ number_format($item->price_per_day ?? 0, 0, ',', '.') }}</td>
                            <td style="padding:8px;text-align:right;">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📋 Langkah Selanjutnya:</h3>
                <ol>
                    <li><strong>Menunggu Verifikasi:</strong> Admin akan memeriksa data Anda dalam waktu maksimal 24 jam</li>
                    <li><strong>Jika Disetujui:</strong> Anda akan menerima email dengan informasi pembayaran</li>
                    <li><strong>Lakukan Pembayaran:</strong> Transfer ke rekening yang tertera dan kirim bukti ke WhatsApp admin</li>
                    <li><strong>Konfirmasi Akhir:</strong> Peralatan akan dikirim sesuai tanggal yang disepakati</li>
                </ol>
            </div>

            <!-- Important -->
            <div class="highlight">
                <strong>⚠️ Penting:</strong> Simpan nomor pesanan <strong>{{ $booking->order_number }}</strong> ini. Anda akan membutuhkannya untuk menghubungi admin atau melacak pesanan.
            </div>

            <!-- Contact -->
            <p style="text-align: center; margin-top: 30px;">
                Pertanyaan? Hubungi admin melalui:
            </p>
            <p style="text-align: center;">
                <a href="https://wa.me/6281234567890" style="color: #27ae60; text-decoration: none; font-weight: bold;">
                    💬 WhatsApp
                </a>
            </p>

            <!-- Footer -->
            <div class="footer">
                <p>Email ini dikirim secara otomatis. Jangan balas email ini.</p>
                <p>&copy; 2026 Satya Palapa Rent. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
