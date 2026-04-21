# Setup Email Pengiriman Invoice

## Masalah Saat Ini
Email dari UKM (m.rizkyfahrizal1410@gmail.com) belum terkirim ke pelanggan karena perlu setup Google App Password.

## Solusi: Menggunakan Gmail SMTP

### Langkah 1: Aktifkan 2-Step Verification di Google Account

1. Buka: https://myaccount.google.com/
2. Klik **Security** di sidebar kiri
3. Cari **2-Step Verification** dan klik
4. Ikuti proses verifikasi (gunakan HP)

### Langkah 2: Generate Google App Password

1. Setelah 2-Step aktif, kembali ke halaman Security
2. Cari **App passwords** (akan muncul setelah 2-Step aktif)
3. Pilih:
   - App: **Mail**
   - Device: **Windows Computer** (atau sesuai OS)
4. Google akan generate 16 karakter password
5. **Salin password tersebut** (tanpa spasi)

### Langkah 3: Update .env File

Buka file `.env` di root project dan ganti:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=m.rizkyfahrizal1410@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="m.rizkyfahrizal1410@gmail.com"
MAIL_FROM_NAME="SatPals UKM"
```

Ganti `MAIL_PASSWORD` dengan 16 karakter yang sudah di-generate (tanpa spasi).

### Langkah 4: Test Email

Buka Artisan Tinker dan kirim test email:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
Mail::raw('Test email dari SatPals', function($message) {
    $message->to('email_tujuan@gmail.com')->subject('Test Email');
});
```

Jika berhasil, email akan terkirim dalam beberapa detik.

### Langkah 5: Approve Band Rental untuk Test Invoice

1. Buka Admin Panel → Permintaan Sewa Band
2. Pilih rental yang status "Menunggu"
3. Klik **Setujui**
4. Isi harga dan discount
5. Klik **Approve** - invoice akan langsung dikirim ke email pelanggan

## Troubleshooting

### Email Tidak Terkirim

**Periksa file log:**
```bash
tail -f storage/logs/laravel.log
```

**Error yang mungkin muncul:**
- "Less secure app access" - Pastikan 2-Step sudah aktif, gunakan App Password
- "Invalid credentials" - Cek password, pastikan tanpa spasi
- "Connection timeout" - Periksa firewall/antivirus

### Email Masuk Spam

Tambahkan email UKM ke contact pelanggan untuk whitelist.

## Informasi Penting

⚠️ **JANGAN**: Commit `.env` ke Git karena berisi password!

✅ **PERLU**: Setup di setiap environment (local, staging, production)

## Production Deployment

Untuk production, gunakan service email profesional seperti:
- **Mailgun** (recommended)
- **SendGrid**
- **AWS SES**

Atau gunakan Gmail + Google Workspace untuk reliability lebih tinggi.

---

**Dibuat:** 20 April 2026
**Status:** Pending Testing
