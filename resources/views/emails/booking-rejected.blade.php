<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Permintaan Ditolak</title>
  <style>
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color: #111827; }
    .container { max-width: 600px; margin: 24px auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px; }
    h1 { font-size: 20px; margin-bottom: 8px; }
    p { margin: 8px 0; color: #374151; }
    .reason { background: #fff7ed; border: 1px solid #ffedd5; padding: 12px; border-radius: 6px; color: #92400e; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Permintaan Anda Ditolak</h1>

    <p>Halo {{ $booking->renter_name ?? $booking->nama_pemohon ?? 'Pelanggan' }},</p>

    <p>Kami mohon maaf. Permintaan pemesanan Anda <strong>
      {{ $booking->booking_code ?? $booking->order_number ?? $booking->kode_order ?? '' }}
    </strong> telah ditolak oleh tim admin.</p>

    @if(!empty($reason))
    <div class="reason">
      <strong>Alasan penolakan:</strong>
      <p>{{ $reason }}</p>
    </div>
    @endif

    <p>Jika Anda butuh bantuan lebih lanjut atau ingin mengajukan ulang, silakan balas email ini atau hubungi kami melalui admin panel.</p>

    <p>Terima kasih,<br>Tim Satya Palapa</p>
  </div>
</body>
</html>
