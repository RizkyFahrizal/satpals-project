<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->booking_code ?? $booking->id }}</title>
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
            background: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            border-bottom: 3px solid #f59e0b;
            padding-bottom: 18px;
        }

        .header-left h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .header-left p,
        .header-right p {
            font-size: 13px;
            color: #6b7280;
        }

        .invoice-no {
            font-size: 18px;
            font-weight: bold;
            color: #f59e0b;
        }

        .section {
            margin-bottom: 22px;
        }

        .section-title {
            background: #fff7ed;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: bold;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .summary {
            margin-top: 10px;
            background: #f9fafb;
            border-left: 4px solid #f59e0b;
            padding: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .summary-row.total {
            border-top: 2px solid #e5e7eb;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 15px;
            color: #b45309;
        }

        .footer {
            margin-top: 30px;
            padding-top: 16px;
            border-top: 2px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }

        .note {
            margin-top: 12px;
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 12px 14px;
            border-radius: 4px;
            font-size: 12px;
            color: #065f46;
        }
    </style>
</head>
<body>
    @php
        $hargaSatuan = $booking->harga_satuan ?? 0;
        $jumlahNonUkm = $booking->jumlah_non_ukm ?? 0;
        $hargaPokok = $booking->harga_pokok ?? ($hargaSatuan * $jumlahNonUkm);
        $diskonNominal = $booking->diskon_nominal ?? 0;
        $hargaFinal = $booking->harga_final ?? max(0, $hargaPokok - $diskonNominal);
    @endphp

    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>INVOICE STUDIO</h1>
                <p>Satya Palapa</p>
            </div>
            <div class="header-right" style="text-align:right;">
                <p class="invoice-no">{{ $booking->booking_code ?? 'SS-' . $booking->id }}</p>
                <p>{{ $booking->created_at ? $booking->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Informasi Booking</div>
            <div class="grid-2">
                <div>
                    <div class="info-item">
                        <div class="info-label">Nama Pemohon</div>
                        <div class="info-value">{{ $booking->nama_pemohon }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $booking->renter_email ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value">{{ $booking->renter_phone ?? '-' }}</div>
                    </div>
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Booking</div>
                        <div class="info-value">{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('l, d F Y') : '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Sesi</div>
                        <div class="info-value">{{ $booking->sesiLabel }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Keperluan</div>
                        <div class="info-value">{{ $booking->keperluan }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Rincian Harga</div>
            <div class="summary">
                <div class="summary-row">
                    <span>Harga per Orang</span>
                    <span>Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Jumlah Non-UKM</span>
                    <span>{{ $jumlahNonUkm }} orang</span>
                </div>
                <div class="summary-row">
                    <span>Total Awal</span>
                    <span>Rp {{ number_format($hargaPokok, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Diskon</span>
                    <span>Rp {{ number_format($diskonNominal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Akhir</span>
                    <span>Rp {{ number_format($hargaFinal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="note">
            Invoice ini dibuat untuk booking studio dan akan digunakan sebagai bukti transaksi setelah booking disetujui.
        </div>

        <div class="footer">
            Terima kasih telah menggunakan layanan booking studio Satya Palapa.
        </div>
    </div>
</body>
</html>
