<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Booking Studio Disetujui</title>
</head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,sans-serif;color:#111827;">
	@php
		$logoPath = public_path('assets/images/logoukm.png');
		$logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
		$bankAccount = env('UKM_BANK_ACCOUNT', '2141375549');
		$paymentContact = $adminWhatsApp ?? config('contact.cp_peralatan', env('CONTACT_CP_PERALATAN', ''));
		$adminNumber = preg_replace('/[^0-9]/', '', $paymentContact);
		$confirmText = rawurlencode('Halo admin, saya telah melakukan pembayaran booking studio ' . ($booking->booking_code ?? $booking->id) . '. Berikut bukti transfer saya.');
	@endphp
	<div style="max-width:640px;margin:0 auto;padding:24px;">
		<div style="background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#fff;padding:28px;border-radius:16px 16px 0 0;text-align:center;">
			@if($logoBase64)
				<img src="{{ $logoBase64 }}" alt="Logo UKM" style="width:54px;height:54px;object-fit:contain;margin:0 auto 12px;display:block;">
			@endif
			<h1 style="margin:0;font-size:28px;">✅ Booking Studio Disetujui</h1>
			<p style="margin:10px 0 0;font-size:14px;opacity:.95;">Satya Palapa</p>
		</div>

		<div style="background:#fff;padding:28px;border-radius:0 0 16px 16px;box-shadow:0 10px 25px rgba(0,0,0,.06);">
			<p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->nama_pemohon }}</strong>, booking studio Anda sudah disetujui admin Satya Palapa.</p>
			<p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">Di email ini juga terlampir invoice PDF sebagai bukti transaksi dan rincian harga booking Anda.</p>

			<div style="background:#fefce8;border-left:4px solid #f59e0b;padding:16px;border-radius:12px;margin:20px 0;">
				<h2 style="margin:0 0 12px;font-size:18px;color:#92400e;">Detail Booking</h2>
				<table style="width:100%;border-collapse:collapse;font-size:14px;">
					@php
						$jumlahNonUkmLabel = (($booking->jumlah_non_ukm ?? 0) > 0) ? ($booking->jumlah_non_ukm . ' orang') : 'UKM semua';
					@endphp
					<tr><td style="padding:8px 0;width:40%;color:#6b7280;">Kode Booking</td><td style="padding:8px 0;font-weight:bold;">{{ $booking->booking_code }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Tanggal</td><td style="padding:8px 0;">{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : '-' }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Sesi</td><td style="padding:8px 0;">{{ $booking->sesiLabel }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Keperluan</td><td style="padding:8px 0;">{{ $booking->keperluan }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Jumlah Non-UKM</td><td style="padding:8px 0;">{{ $jumlahNonUkmLabel }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Harga Awal</td><td style="padding:8px 0;">Rp {{ number_format($booking->harga_pokok, 0, ',', '.') }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Diskon</td><td style="padding:8px 0;">Rp {{ number_format($booking->diskon_nominal, 0, ',', '.') }}</td></tr>
					<tr><td style="padding:8px 0;color:#6b7280;">Total Akhir</td><td style="padding:8px 0;font-weight:bold;color:#166534;">Rp {{ number_format($booking->harga_final, 0, ',', '.') }}</td></tr>
				</table>
			</div>

			@if(($booking->harga_final ?? 0) > 0)
				<div style="background:#dcfce7;border-left:4px solid #22c55e;padding:16px;border-radius:12px;margin:20px 0;">
					<h3 style="margin:0 0 12px;font-size:16px;color:#15803d;">💰 Informasi Pembayaran</h3>
					<p style="margin:0 0 10px;font-size:13px;color:#166534;line-height:1.6;">Silakan transfer ke rekening UKM berikut:</p>
					<div style="background:#fff;border:2px solid #22c55e;padding:12px;border-radius:8px;text-align:center;">
						<p style="margin:0 0 4px;font-size:12px;color:#6b7280;">Nomor Rekening (BCA)</p>
						<p style="margin:0;font-size:16px;font-weight:bold;color:#15803d;letter-spacing:1px;">{{ $bankAccount }}</p>
					</div>
				</div>
			@endif

			<div style="background:#ecfdf5;border-left:4px solid #10b981;padding:16px;border-radius:12px;margin:20px 0;">
				<p style="margin:0 0 10px;font-size:14px;color:#065f46;line-height:1.6;">Setelah transfer, silakan kirim bukti pembayaran ke CP Peralatan melalui WhatsApp di bawah ini:</p>
				<a href="https://wa.me/{{ $adminNumber }}?text={{ $confirmText }}" style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">💬 Kirim Bukti Pembayaran</a>
				<p style="margin:10px 0 0;font-size:12px;color:#047857;">Kontak CP Peralatan: {{ $paymentContact }}</p>
			</div>

			<p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
		</div>
	</div>
</body>
</html>
