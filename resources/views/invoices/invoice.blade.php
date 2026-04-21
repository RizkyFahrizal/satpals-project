<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
        }
        
        .header-left h1 {
            font-size: 28px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .header-left p {
            color: #666;
            font-size: 13px;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right p {
            margin: 3px 0;
            font-size: 12px;
        }
        
        .invoice-no {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }
        
        /* Section */
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #f0f5ff;
            border-left: 4px solid #1e40af;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #1e40af;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-size: 11px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 13px;
        }
        
        .items-table thead {
            background: #f0f5ff;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
        }
        
        .items-table th {
            padding: 10px;
            text-align: left;
            color: #1e40af;
            font-weight: 600;
            border: none;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        /* Calculation Box */
        .calculation {
            margin: 20px 0;
            padding: 15px;
            background: #f9fafb;
            border-left: 4px solid #1e40af;
        }
        
        .calc-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .calc-label {
            color: #666;
        }
        
        .calc-value {
            font-weight: 500;
            color: #333;
            text-align: right;
            min-width: 120px;
        }
        
        .calc-row.total {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #1e40af;
            font-size: 15px;
            font-weight: bold;
        }
        
        .calc-row.total .calc-label {
            color: #1e40af;
        }
        
        .calc-row.total .calc-value {
            color: #1e40af;
            font-size: 16px;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        
        .footer-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .highlight {
            background: #fffacd;
            padding: 2px 6px;
        }
        
        /* Currency */
        .currency {
            font-size: 14px;
            color: #1e40af;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>INVOICE</h1>
                <p>Pesawaran Timur Student Orchestra</p>
            </div>
            <div class="header-right">
                <p><span class="invoice-no">{{ $invoiceNumber }}</span></p>
                <p style="margin-top: 5px;">{{ $invoiceDate }}</p>
            </div>
        </div>

        <!-- Band Information -->
        <div class="section">
            <div class="section-title">Informasi Band</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama Band</div>
                        <div class="info-value">{{ $band->band_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Harga Per Jam</div>
                        <div class="info-value">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Durasi Main</div>
                        <div class="info-value">{{ $durationHours }} Jam</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Renter Information -->
        <div class="section">
            <div class="section-title">Data Penyewa</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama Penyewa</div>
                        <div class="info-value">{{ $rental->renter_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value">{{ $rental->renter_phone }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Pertunjukan</div>
                        <div class="info-value">{{ $rental->performance_date->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="info-item" style="margin-top: 15px;">
                <div class="info-label">Tujuan Penyewaan</div>
                <div class="info-value">{{ $rental->rental_purpose }}</div>
            </div>
        </div>

        <!-- Performance Details -->
        <div class="section">
            <div class="section-title">Detail Pertunjukan</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <div class="info-label">Waktu Mulai</div>
                        <div class="info-value">{{ $rental->performance_start_time ?? '-' }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Waktu Berakhir</div>
                        <div class="info-value">{{ $rental->performance_end_time ?? '-' }}</div>
                    </div>
                </div>
            </div>
            @if($rental->venue_address)
            <div>
                <div class="info-item">
                    <div class="info-label">Lokasi/Alamat Pertunjukan</div>
                    <div class="info-value">{{ $rental->venue_address }}</div>
                </div>
            </div>
            @endif
        </div>

        <!-- Pricing -->
        <div class="section">
            <div class="section-title">Perhitungan Harga</div>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="text-left">Uraian</th>
                        <th class="text-right">Satuan</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">Harga Penyewaan Band {{ $band->band_name }}</td>
                        <td class="text-right">{{ $durationHours }} jam</td>
                        <td class="text-right">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rental->harga_pokok, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="calculation">
                @if($rental->diskon_nominal > 0)
                <div class="calc-row">
                    <span class="calc-label">Harga Pokok</span>
                    <span class="calc-value">Rp {{ number_format($rental->harga_pokok, 0, ',', '.') }}</span>
                </div>
                <div class="calc-row">
                    <span class="calc-label">Diskon ({{ $rental->diskon_persen }}%)</span>
                    <span class="calc-value">- Rp {{ number_format($rental->diskon_nominal, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="calc-row total">
                    <span class="calc-label">TOTAL HARGA</span>
                    <span class="calc-value">Rp {{ number_format($rental->harga_final, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <strong>⚠️ Catatan Penting:</strong><br>
            • Pembayaran harus dilakukan sebelum tanggal pertunjukan<br>
            • Harap transfer ke rekening yang telah disediakan<br>
            • Bukti transfer harus dikonfirmasi ke admin
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Invoice ini dibuat secara otomatis oleh sistem SATPAL</p>
            <p>Untuk informasi lebih lanjut, hubungi administrator</p>
            <p style="margin-top: 10px; color: #ccc;">Pesawaran Timur Student Orchestra</p>
        </div>
    </div>
</body>
</html>
