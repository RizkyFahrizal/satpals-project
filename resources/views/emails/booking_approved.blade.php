<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Persewaan Alat Disetujui</title>
</head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,sans-serif;color:#111827;">
    @php
        $bankAccount = env('UKM_BANK_ACCOUNT', '2141375549');
        $adminNumber = preg_replace('/[^0-9]/', '', $adminWhatsApp ?? config('contact.cp_peralatan', env('CONTACT_CP_PERALATAN', '')));
        $confirmText = rawurlencode('Halo admin, saya telah melakukan pembayaran untuk pesanan alat ' . $booking->order_number . '. Berikut bukti transfer saya.');
        $itemSummary = $booking->items->map(function ($item) {
            return $item->equipment->name . ' x' . $item->quantity;
        })->implode(', ');
    @endphp

    <div style="max-width:640px;margin:0 auto;padding:24px;">
        <div style="background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#fff;padding:28px;border-radius:16px 16px 0 0;text-align:center;">
            <h1 style="margin:0;font-size:28px;">✅ Permintaan Disetujui</h1>
            <p style="margin:10px 0 0;font-size:14px;opacity:.95;">Satya Palapa</p>
        </div>

        <div style="background:#fff;padding:28px;border-radius:0 0 16px 16px;box-shadow:0 10px 25px rgba(0,0,0,.06);">
            <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->renter_name }}</strong>, permintaan persewaan alat Anda sudah <strong>disetujui</strong> oleh admin.</p>
            <p style="margin:0 0 20px;font-size:14px;line-height:1.6;color:#374151;">Invoice PDF sudah terlampir di email ini. Anda juga bisa membuka detail invoice lewat tombol di bawah.</p>

            <div style="background:#eff6ff;border-left:4px solid #3b82f6;padding:16px;border-radius:12px;margin:20px 0;">
                <h2 style="margin:0 0 12px;font-size:18px;color:#1d4ed8;">Detail Pesanan</h2>
                <table style="width:100%;border-collapse:collapse;font-size:14px;">
                    <tr><td style="padding:8px 0;width:40%;color:#6b7280;">Nomor Pesanan</td><td style="padding:8px 0;font-weight:bold;">{{ $booking->order_number }}</td></tr>
                    <tr><td style="padding:8px 0;color:#6b7280;">Tanggal Mulai</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($booking->start_date)->translatedFormat('d F Y') }}</td></tr>
                    <tr><td style="padding:8px 0;color:#6b7280;">Tanggal Selesai</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($booking->end_date)->translatedFormat('d F Y') }}</td></tr>
                    <tr><td style="padding:8px 0;color:#6b7280;">Durasi</td><td style="padding:8px 0;">{{ $booking->duration_days }} hari</td></tr>
                    @php
                        $hargaPokok = (int) ($booking->harga_pokok ?? $booking->total_price ?? 0);
                        $diskonPersen = (int) ($booking->diskon_persen ?? 0);
                        $diskonNominal = (int) ($booking->diskon_nominal ?? 0);
                        $hargaFinal = (int) ($booking->harga_final ?? max(0, $hargaPokok - $diskonNominal));
                    @endphp
                    @if($diskonNominal > 0)
                        <tr><td style="padding:8px 0;color:#6b7280;">Harga Awal</td><td style="padding:8px 0;text-decoration:line-through;color:#9ca3af;">Rp {{ number_format($hargaPokok, 0, ',', '.') }}</td></tr>
                        <tr><td style="padding:8px 0;color:#6b7280;">Diskon</td><td style="padding:8px 0;color:#ef4444;">- Rp {{ number_format($diskonNominal, 0, ',', '.') }} ({{ $diskonPersen }}%)</td></tr>
                    @endif
                    <tr><td style="padding:8px 0;color:#6b7280;">Total Harga</td><td style="padding:8px 0;font-weight:bold;color:#166534;">Rp {{ number_format($hargaFinal, 0, ',', '.') }}</td></tr>
                </table>
            </div>

            <div style="background:#f8fafc;border-left:4px solid #94a3b8;padding:16px;border-radius:12px;margin:20px 0;">
                <h3 style="margin:0 0 10px;font-size:16px;color:#334155;">Barang Disewa</h3>
                <p style="margin:0;font-size:14px;line-height:1.7;color:#374151;">{{ $itemSummary ?: 'Detail barang tersedia di invoice.' }}</p>
            </div>

            <div style="background:#fefce8;border-left:4px solid #f59e0b;padding:16px;border-radius:12px;margin:20px 0;">
                <h3 style="margin:0 0 12px;font-size:16px;color:#92400e;">Instruksi Pembayaran</h3>
                <p style="margin:0 0 8px;font-size:14px;color:#78350f;line-height:1.6;">Silakan transfer ke rekening berikut:</p>
                <p style="margin:0 0 12px;font-size:16px;font-weight:bold;color:#92400e;">{{ $bankAccount }}</p>
                <p style="margin:0;font-size:13px;color:#78350f;line-height:1.6;">Setelah transfer, kirim bukti pembayaran ke CP Peralatan via WhatsApp.</p>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;margin:20px 0;">
                <a href="{{ route('invoice.view', $booking->id) }}" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">Lihat Invoice</a>
                <a href="{{ route('invoice.download', $booking->id) }}" style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">Download Invoice</a>
                @if($adminNumber)
                    <a href="https://wa.me/{{ $adminNumber }}?text={{ $confirmText }}" style="display:inline-block;background:#16a34a;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">💬 Konfirmasi via WhatsApp</a>
                @endif
            </div>

            <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;">Terima kasih telah mempercayai <strong>Satya Palapa</strong>.</p>
        </div>
    </div>
</body>
</html>
