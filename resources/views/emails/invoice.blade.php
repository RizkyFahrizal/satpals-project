<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $booking->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        .page {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 3px solid #f59e0b;
            padding-bottom: 20px;
        }

        .company-info h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #666;
            font-size: 13px;
            margin: 3px 0;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 24px;
            color: #f59e0b;
            margin-bottom: 10px;
        }

        .invoice-meta {
            text-align: right;
            font-size: 13px;
            color: #666;
        }

        .invoice-meta p {
            margin: 3px 0;
        }

        /* Main Content */
        .content {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }

        /* Customer & Invoice Info */
        .info-grid {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }

        .info-column {
            flex: 1;
            font-size: 13px;
        }

        .info-label {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .info-value {
            color: #666;
            margin-bottom: 8px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }

        .items-table thead {
            background: linear-gradient(to right, #fcd34d, #f59e0b);
            color: #1f2937;
            font-weight: bold;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            border: none;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tbody tr:hover {
            background: #fef3c7;
        }

        .items-table td {
            padding: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: bold;
            color: #1f2937;
        }

        /* Summary Section */
        .summary {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .summary-box {
            width: 350px;
            border-left: 3px solid #f59e0b;
            padding-left: 20px;
            font-size: 13px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-row.total {
            border-top: 2px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
            padding-top: 8px;
            padding-bottom: 8px;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 14px;
            color: #f59e0b;
        }

        /* Payment Info */
        .payment-info {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
        }

        .payment-info h4 {
            color: #92400e;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .payment-info p {
            color: #78350f;
            margin: 4px 0;
        }

        /* Notes */
        .notes {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 4px;
            font-size: 12px;
            margin: 20px 0;
            color: #666;
        }

        .notes h4 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 13px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left {
            text-align: left;
        }

        .footer-center {
            text-align: center;
            flex: 1;
        }

        .footer-right {
            text-align: right;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #7f1d1d;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }
            .page {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

        .rental-details {
            display: flex;
            gap: 20px;
            font-size: 13px;
            margin: 15px 0;
        }

        .detail-item {
            flex: 1;
        }

        .detail-label {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .detail-value {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>Satya Palapa</h1>
                <p><strong>UKM Musik</strong></p>
                <p>Penyewaan Alat Musik & Peralatan Event</p>
                <p>📞 +62 812-3456-789</p>
                <p>📧 info@satyapalapa.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-meta">
                    <p><strong>No. Invoice:</strong> {{ $booking->order_number }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</p>
                    <p>
                        <strong>Status:</strong> 
                        @if($booking->status === 'approved')
                        <span class="status-badge status-approved">✓ DISETUJUI</span>
                        @elseif($booking->status === 'pending')
                        <span class="status-badge status-pending">⏳ MENUNGGU</span>
                        @else
                        <span class="status-badge status-rejected">✗ DITOLAK</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Invoice & Customer Info -->
        <div class="content">
            <div class="section-title">Informasi Penyewa</div>
            <div class="info-grid">
                <div class="info-column">
                    <div class="info-label">Nama Penyewa</div>
                    <div class="info-value">{{ $booking->renter_name }}</div>

                    <div class="info-label">NPM / NIK</div>
                    <div class="info-value">{{ $booking->renter_npm_nik }}</div>

                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $booking->renter_phone }}</div>
                </div>
                <div class="info-column">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $booking->renter_email }}</div>

                    <div class="info-label">Lokasi Penyewaan</div>
                    <div class="info-value">{{ $booking->rental_location }}</div>

                    <div class="info-label">Catatan</div>
                    <div class="info-value">{{ $booking->renter_notes ?? '-' }}</div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="section-title">Periode Penyewaan</div>
            <div class="rental-details">
                <div class="detail-item">
                    <div class="detail-label">Tanggal Mulai</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tanggal Selesai</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Durasi Penyewaan</div>
                    <div class="detail-value"><strong>{{ $booking->duration_days }} Hari</strong></div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="section-title">Detail Peralatan</div>
            @php
                $subtotal = (int) ($booking->harga_pokok ?? $booking->total_price ?? 0);
                $diskonPersen = (int) ($booking->diskon_persen ?? 0);
                $diskonNominal = (int) ($booking->diskon_nominal ?? 0);
                $totalPembayaran = (int) ($booking->harga_final ?? max(0, $subtotal - $diskonNominal));
            @endphp
            <table class="items-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Paket / Alat</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga/Hari</th>
                        <th class="text-right">Durasi</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->equipment->name }}</strong><br>
                            <small>{{ ucfirst($item->equipment->category) }}</small>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right amount">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $booking->duration_days }} hari</td>
                        <td class="text-right amount">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Diskon:</span>
                        <span>- Rp {{ number_format($diskonNominal, 0, ',', '.') }} ({{ $diskonPersen }}%)</span>
                    </div>
                    <div class="summary-row total">
                        <span>TOTAL PEMBAYARAN:</span>
                        <span>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="payment-info">
                <h4>📋 INFORMASI PEMBAYARAN</h4>
                @php
                    $bankName = env('UKM_BANK_NAME', 'CIMB Niaga');
                    $bankAccount = env('UKM_BANK_ACCOUNT', '2141375549');
                    $bankAccountName = env('UKM_BANK_ACCOUNT_NAME', 'ZAHLUL NOER LAILY');
                @endphp
                <p><strong>Status Pembayaran:</strong> 
                    @if($booking->payment_status === 'paid')
                        <span style="color: #059669; font-weight: bold;">✓ LUNAS</span>
                    @else
                        <span style="color: #d97706; font-weight: bold;">Menunggu Pembayaran</span>
                    @endif
                </p>
                <p><strong>Nama Bank:</strong> {{ $bankName }}</p>
                <p><strong>Nomor Rekening:</strong> {{ $bankAccount }}</p>
                <p><strong>Atas Nama:</strong> {{ $bankAccountName }}</p>
                <p><strong>Cara Pembayaran:</strong> Transfer Bank / Tunai</p>
                <p><strong>Batas Pembayaran:</strong> Sebelum {{ \Carbon\Carbon::parse($booking->start_date)->subDay()->format('d M Y') }}</p>
            </div>

            <!-- Notes -->
            @if($booking->renter_notes)
            <div class="notes">
                <h4>📝 Catatan Tambahan</h4>
                <p>{{ $booking->renter_notes }}</p>
            </div>
            @endif

            <!-- Terms -->
            <div class="notes">
                <h4>⚠️ Syarat & Ketentuan</h4>
                <p>
                    1. Penyewa bertanggung jawab atas keamanan dan perawatan peralatan yang disewa.<br>
                    2. Keterlambatan pengembalian akan dikenakan biaya tambahan Rp 50.000/jam.<br>
                    3. Kerusakan atau kehilangan peralatan akan dibebankan ke penyewa.<br>
                    4. Pembayaran harus dilakukan tepat waktu sesuai tanggal yang ditentukan.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <p><strong>Satya Palapa UKM</strong></p>
                <p>UPN "Veteran" Jawa Timur</p>
            </div>
            <div class="footer-center">
                <p>Terima kasih telah mempercayai kami!</p>
            </div>
            <div class="footer-right">
                <p>Diunduh: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
