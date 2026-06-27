<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Permintaan Ditolak</title>
  <style>
    body { margin: 0; padding: 0; background: #f9fafb; font-family: Arial, sans-serif; color: #111827; }
    .container { max-width: 640px; margin: 0 auto; padding: 24px; }
    .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; padding: 28px; border-radius: 16px 16px 0 0; text-align: center; }
    .header h1 { margin: 0; font-size: 28px; }
    .content { background: #fff; padding: 28px; border-radius: 0 0 16px 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    .section { padding: 16px; border-radius: 12px; margin: 20px 0; }
    .reason-box { background: #fee2e2; border-left: 4px solid #dc2626; }
    .detail-box { background: #f3f4f6; border-left: 4px solid #6b7280; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    td { padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
    .label { color: #6b7280; width: 40%; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>❌ Permintaan Ditolak</h1>
      <p>Satya Palapa</p>
    </div>

    <div class="content">
      <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $customerName ?? 'Pelanggan' }}</strong>,</p>

      <p style="margin:0 0 16px;font-size:14px;color:#374151;">Kami mohon maaf. Permintaan pemesanan Anda telah ditolak oleh tim admin.</p>

      @if(!empty($reason))
      <div class="section reason-box">
        <h3 style="margin:0 0 12px;font-size:16px;color:#991b1b;">📋 Alasan Penolakan</h3>
        <p style="margin:0;font-size:14px;color:#7f1d1d;">{{ $reason }}</p>
      </div>
      @endif

      <!-- Detail Permintaan -->
      <div class="section detail-box">
        <h3 style="margin:0 0 12px;font-size:16px;color:#374151;">📝 Detail Permintaan</h3>
        <table>
          <tr>
            <td class="label">No. Referensi</td>
            <td style="color:#1f2937;font-weight:500;">{{ $referenceCode ?? '-' }}</td>
          </tr>
          @if(!empty($requestDateLabel) && $requestDateLabel !== '-')
          <tr>
            <td class="label">Tanggal Permintaan</td>
            <td style="color:#1f2937;font-weight:500;">{{ $requestDateLabel }}</td>
          </tr>
          @endif
          @if(!is_null($amount))
          <tr style="border-bottom:2px solid #d1d5db;">
            <td class="label">Jumlah</td>
            <td style="color:#1f2937;font-weight:bold;font-size:15px;">Rp {{ number_format($amount ?? 0, 0, ',', '.') }}</td>
          </tr>
          @endif
        </table>
      </div>

      <p style="margin:16px 0;font-size:14px;color:#374151;">Jika Anda butuh bantuan lebih lanjut atau ingin mengajukan ulang, silakan balas email ini atau hubungi kami melalui admin panel.</p>

      <p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Tim Satya Palapa</strong></p>
    </div>
  </div>
</body>
</html>
