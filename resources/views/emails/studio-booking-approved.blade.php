<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking Studio Disetujui</title>
</head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,sans-serif;color:#111827;">
	<div style="max-width:640px;margin:0 auto;padding:24px;">
		<div style="background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#fff;padding:28px;border-radius:16px 16px 0 0;text-align:center;">
			<h1 style="margin:0;font-size:28px;">✅ Booking Studio Disetujui</h1>
			<p style="margin:10px 0 0;font-size:14px;opacity:.95;">Satya Palapa</p>
		</div>

		<div style="background:#fff;padding:28px;border-radius:0 0 16px 16px;box-shadow:0 10px 25px rgba(0,0,0,.06);">
			<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->nama_pemohon }}</strong>, booking studio Anda sudah disetujui admin Satya Palapa.</p>

			<div style="background:#fefce8;border-left:4px solid #f59e0b;padding:16px;border-radius:12px;margin:20px 0;">
				<h2 style="margin:0 0 12px;font-size:18px;color:#92400e;">Detail Booking</h2>
				<table style="width:100%;border-collapse:collapse;font-size:14px;">
					<tr><td style="padding:8px 0;width:40%;color:#6b7280;">Kode Booking</td><td style="padding:8px 0;font-weight:bold;">{{ $booking->booking_code }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Tanggal</td><td style="padding:8px 0;">{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : '-' }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Sesi</td><td style="padding:8px 0;">{{ $booking->sesiLabel }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Keperluan</td><td style="padding:8px 0;">{{ $booking->keperluan }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Jumlah Non-UKM</td><td style="padding:8px 0;">{{ $booking->jumlah_non_ukm }} orang</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Harga Awal</td><td style="padding:8px 0;">Rp {{ number_format($booking->harga_pokok, 0, ',', '.') }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Diskon</td><td style="padding:8px 0;">Rp {{ number_format($booking->diskon_nominal, 0, ',', '.') }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Total Akhir</td><td style="padding:8px 0;font-weight:bold;color:#166534;">Rp {{ number_format($booking->harga_final, 0, ',', '.') }}</td></tr>
				</table>
			</div>

			<div style="background:#ecfdf5;border-left:4px solid #10b981;padding:16px;border-radius:12px;margin:20px 0;">
				<p style="margin:0 0 10px;font-size:14px;color:#065f46;line-height:1.6;">Jika ada pertanyaan, silakan hubungi admin via WhatsApp:</p>
				<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $adminWhatsApp) }}" style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">💬 Chat Admin</a>
			</div>

			<p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
		</div>
	</div>
</body>
</html>
