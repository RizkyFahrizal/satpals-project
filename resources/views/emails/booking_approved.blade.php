@component('mail::message')
# 🎉 Pesanan Anda Telah Disetujui!

Halo **{{ $booking->renter_name }}**,

Kami dengan senang hati menginformasikan bahwa pesanan Anda telah **disetujui** oleh admin Satya Palapa.

## 📋 Detail Pesanan

| Keterangan | Informasi |
|:---|:---|
| **Nomor Pesanan** | {{ $booking->order_number }} |
| **Tanggal Mulai** | {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} |
| **Tanggal Selesai** | {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }} |
| **Durasi** | {{ $booking->duration_days }} Hari |
| **Total Harga** | Rp {{ number_format($booking->total_price, 0, ',', '.') }} |
| **Status** | ✓ Disetujui |

## 📦 Peralatan yang Disewa

@foreach($booking->items as $item)
- **{{ $item->equipment->name }}** ({{ ucfirst($item->equipment->category) }})
  - Jumlah: {{ $item->quantity }} unit
  - Harga/hari: Rp {{ number_format($item->price_per_day, 0, ',', '.') }}
  - Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
@endforeach

## 💳 Instruksi Pembayaran

Untuk menyelesaikan proses penyewaan, silakan lakukan pembayaran sesuai dengan langkah-langkah berikut:

1. **Transfer Pembayaran**
   - Transfer total Rp {{ number_format($booking->total_price, 0, ',', '.') }} ke rekening Satya Palapa
   - Rekening details akan dikirim melalui email terpisah

2. **Konfirmasi Pembayaran**
   - Kirim bukti transfer (screenshot) ke WhatsApp admin
   - Sertakan nomor pesanan: **{{ $booking->order_number }}**

3. **Tunggu Konfirmasi**
   - Admin akan melakukan verifikasi pembayaran
   - Setelah verifikasi, peralatan siap untuk diambil/dikirim

4. **Pengambilan Peralatan**
   - Lokasi pengambilan: {{ $booking->rental_location }}
   - Waktu pengambilan akan dikonfirmasi oleh admin

---

## 📄 Invoice

Invoice lengkap telah dilampirkan dalam email ini. Anda juga dapat mengunduhnya kapan saja melalui link berikut:

@component('mail::button', ['url' => route('invoice.view', $booking->id), 'color' => 'primary'])
Lihat Invoice Online
@endcomponent

@component('mail::button', ['url' => route('invoice.download', $booking->id), 'color' => 'success'])
Download Invoice PDF
@endcomponent

---

## 📞 Hubungi Admin

Jika Anda memiliki pertanyaan atau membutuhkan bantuan, silakan hubungi admin kami:

@component('mail::button', ['url' => 'https://wa.me/628123456789?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20tentang%20pesanan%20' . $booking->order_number, 'color' => 'success'])
💬 Chat Admin via WhatsApp
@endcomponent

---

Terima kasih telah mempercayai **Satya Palapa** sebagai mitra penyewaan alat musik Anda!

**Satya Palapa UKM**  
Penyewaan Alat Musik & Peralatan Event  
UPN "Veteran" Jawa Timur

@endcomponent
