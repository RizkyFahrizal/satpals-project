<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesanan Disetujui</title>
</head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial,sans-serif;color:#111827;">
   @php
      $logoPath = public_path('assets/images/logoukm.png');
      $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
   @endphp
   <div style="max-width:640px;margin:0 auto;padding:24px;">
      <div style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);color:#fff;padding:28px;border-radius:16px 16px 0 0;text-align:center;">
         @if($logoBase64)
            <img src="{{ $logoBase64 }}" alt="Logo UKM" style="width:54px;height:54px;object-fit:contain;margin:0 auto 12px;display:block;">
         @endif
         <h1 style="margin:0;font-size:28px;">🎉 Pesanan Anda Telah Disetujui</h1>
         <p style="margin:10px 0 0;font-size:14px;opacity:.95;">Satya Palapa</p>
      </div>

      <div style="background:#fff;padding:28px;border-radius:0 0 16px 16px;box-shadow:0 10px 25px rgba(0,0,0,.06);">
         <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo <strong>{{ $booking->renter_name }}</strong>, kami dengan senang hati menginformasikan bahwa pesanan Anda telah <strong>disetujui</strong> oleh admin Satya Palapa.</p>

         <div style="background:#eff6ff;border-left:4px solid #3b82f6;padding:16px;border-radius:12px;margin:20px 0;">
            <h2 style="margin:0 0 12px;font-size:18px;color:#1d4ed8;">Detail Pesanan</h2>
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
               <tr><td style="padding:8px 0;width:40%;color:#6b7280;">Nomor Pesanan</td><td style="padding:8px 0;">{{ $booking->order_number }}</td></tr>
               <tr><td style="padding:8px 0;color:#6b7280;">Tanggal Mulai</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td></tr>
               <tr><td style="padding:8px 0;color:#6b7280;">Tanggal Selesai</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</td></tr>
               <tr><td style="padding:8px 0;color:#6b7280;">Durasi</td><td style="padding:8px 0;">{{ $booking->duration_days }} Hari</td></tr>
               <tr><td style="padding:8px 0;color:#6b7280;">Total Harga</td><td style="padding:8px 0;">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td></tr>
               <tr><td style="padding:8px 0;color:#6b7280;">Status</td><td style="padding:8px 0;">✓ Disetujui</td></tr>
            </table>
         </div>

         <div style="background:#f8fafc;border-left:4px solid #94a3b8;padding:16px;border-radius:12px;margin:20px 0;">
            <h3 style="margin:0 0 12px;font-size:16px;color:#334155;">Peralatan yang Disewa</h3>
            <ul style="margin:0;padding-left:20px;font-size:14px;line-height:1.7;color:#374151;">
               @foreach($booking->items as $item)
                  <li>
                     <strong>{{ $item->equipment->name }}</strong> ({{ ucfirst($item->equipment->category) }})<br>
                     Jumlah: {{ $item->quantity }} unit · Harga/hari: Rp {{ number_format($item->price_per_day, 0, ',', '.') }} · Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                  </li>
               @endforeach
            </ul>
         </div>

         <div style="background:#fefce8;border-left:4px solid #f59e0b;padding:16px;border-radius:12px;margin:20px 0;">
            <h3 style="margin:0 0 12px;font-size:16px;color:#92400e;">Instruksi Pembayaran</h3>
            <ol style="margin:0;padding-left:20px;font-size:14px;line-height:1.7;color:#374151;">
               <li><strong>Transfer Pembayaran</strong><br>Transfer total Rp {{ number_format($booking->total_price, 0, ',', '.') }} ke rekening Satya Palapa.</li>
               <li><strong>Konfirmasi Pembayaran</strong><br>Kirim bukti transfer (screenshot) ke WhatsApp admin dan sertakan nomor pesanan <strong>{{ $booking->order_number }}</strong>.</li>
               <li><strong>Tunggu Konfirmasi</strong><br>Admin akan melakukan verifikasi pembayaran sebelum peralatan siap diambil/dikirim.</li>
               <li><strong>Pengambilan Peralatan</strong><br>Lokasi pengambilan: {{ $booking->rental_location }}.</li>
            </ol>
         </div>

         <div style="display:flex;gap:12px;flex-wrap:wrap;margin:20px 0;">
            <a href="{{ route('invoice.view', $booking->id) }}" style="display:inline-block;background:#2563eb;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">Lihat Invoice Online</a>
            <a href="{{ route('invoice.download', $booking->id) }}" style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">Download Invoice PDF</a>
            <a href="https://wa.me/628123456789?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20tentang%20pesanan%20{{ $booking->order_number }}" style="display:inline-block;background:#16a34a;color:#fff;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:bold;">💬 Chat Admin via WhatsApp</a>
         </div>

         <p style="margin:0;font-size:14px;color:#374151;line-height:1.6;">Terima kasih telah mempercayai <strong>Satya Palapa</strong> sebagai mitra penyewaan alat musik Anda!<br><br><strong>Satya Palapa UKM</strong><br>Penyewaan Alat Musik &amp; Peralatan Event<br>UPN "Veteran" Jawa Timur</p>
      </div>
   </div>
</body>
</html>
