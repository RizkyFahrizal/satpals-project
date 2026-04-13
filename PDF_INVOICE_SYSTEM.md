# Dokumentasi PDF Invoice System - Equipment Rental

## Ringkasan Fitur

Sistem ini menghasilkan Invoice PDF profesional untuk booking peralatan rental saat pesanan disetujui oleh admin. Invoice otomatis dikirim melalui email dengan PDF attachment dan tersedia untuk diunduh kapan saja.

## Komponen Sistem

### 1. **Controller: InvoiceController**
Lokasi: `app/Http/Controllers/InvoiceController.php`

**Methods:**
- `download($id)` - Download invoice PDF
- `view($id)` - Display invoice di browser
- `getContent($id)` - Get PDF content untuk attachment email

```php
// Usage
GET /invoices/{bookingId}/download    // Download PDF
GET /invoices/{bookingId}/view        // View PDF online
```

### 2. **Blade Template: Invoice**
Lokasi: `resources/views/emails/invoice.blade.php`

**Content:**
- Header dengan logo Satya Palapa
- Nomor invoice dan status
- Informasi penyewa (nama, NPM/NIK, email, telepon)
- Detail periode penyewaan (tanggal mulai, selesai, durasi)
- Tabel item peralatan dengan harga per hari dan subtotal
- Ringkasan pembayaran (subtotal, pajak 10%, total)
- Informasi pembayaran dan syarat ketentuan
- Footer dengan info kontak

**Data Variables:**
```php
$booking              // EquipmentRentalRequest model
$booking->items      // Collection of EquipmentRentalRequestItem
```

### 3. **Mailable: BookingApprovedMail**
Lokasi: `app/Mail/BookingApprovedMail.php`

**Fitur:**
- Email dengan design profesional
- Auto-attach PDF invoice
- Template: `emails.booking_approved`
- Menggunakan Markdown for Mail

**Usage:**
```php
Mail::to($email)->send(new BookingApprovedMail($booking));
```

### 4. **Email Template**
Lokasi: `resources/views/emails/booking_approved.blade.php`

**Content:**
- Greeting personal ke penyewa
- Ringkasan detail pesanan dalam tabel
- Daftar peralatan yang disewa
- Instruksi pembayaran step-by-step
- Button untuk lihat/download invoice
- Contact admin via WhatsApp

### 5. **Routes**
```php
Route::get('/invoices/{booking}/download', [InvoiceController::class, 'download'])->name('invoice.download');
Route::get('/invoices/{booking}/view', [InvoiceController::class, 'view'])->name('invoice.view');
```

### 6. **Admin Workflow**
Lokasi: `app/Http/Controllers/Admin/EquipmentRentalRequestController.php`

**Approve Method:**
```php
public function approve($id)
{
    // ... validation
    
    $rentalRequest->update([
        'status' => 'approved',
        'admin_notes' => '...'
    ]);
    
    // Send email with PDF invoice
    $this->sendApprovalEmail($rentalRequest);
    
    // Returns success message
}
```

## Alur Kerja

### Scenario: Booking Disetujui

```
1. Admin membuka halaman Equipment Rental Requests
   ↓
2. Admin melihat pending requests
   ↓
3. Admin klik tombol "Setujui" pada pesanan
   ↓
4. System update status ke 'approved'
   ↓
5. System generate PDF invoice
   ↓
6. System kirim email ke penyewa dengan:
   - Subject: "Pesanan Disetujui - Invoice {order_number}"
   - Body: HTML template dengan detail pesanan
   - Attachment: Invoice-{order_number}.pdf
   ↓
7. Penyewa menerima email dengan invoice
```

### Scenario: Download Invoice Manual

```
1. Penyewa login atau buka halaman /bookings
   ↓
2. Penyewa klik tombol "Download Invoice" pada booking yang disetujui
   ↓
3. Browser download file PDF
   
Atau:

1. Penyewa klik tombol "Lihat Invoice"
   ↓
2. Invoice tampil di browser dalam format PDF
```

## Customization Guide

### 1. Mengubah Layout Invoice

Edit file: `resources/views/emails/invoice.blade.php`

**Sections yang bisa diubah:**
- Header (logo, company info)
- Color theme (yellow/orange gradient)
- Tabel items
- Format uang (Rp format)
- Syarat & ketentuan
- Footer

**Example - Mengganti warna:**
```html
<!-- Ganti dari: -->
<div class="header" style="border-bottom: 3px solid #f59e0b;">

<!-- Menjadi: -->
<div class="header" style="border-bottom: 3px solid #3b82f6;">
```

### 2. Menambah Data ke Invoice

Edit `app/Http/Controllers/InvoiceController.php`:

```php
public function download($id)
{
    $booking = EquipmentRentalRequest::with('items.equipment')->findOrFail($id);
    
    // Tambah data custom
    $booking->custom_data = 'value';
    
    $pdf = PDF::loadView('emails.invoice', [
        'booking' => $booking,
        'additional' => 'data'
    ]);
    
    return $pdf->download('Invoice-' . $booking->order_number . '.pdf');
}
```

Kemudian gunakan di template:
```blade
{{ $additional ?? '' }}
```

### 3. Mengubah Email Template

Edit file: `resources/views/emails/booking_approved.blade.php`

**Gunakan Blade Mail Components:**
```blade
@component('mail::button', ['url' => $url])
Click Me
@endcomponent
```

### 4. Mengubah Format Nama File PDF

Edit `app/Http/Controllers/InvoiceController.php`:

```php
// Default format: Invoice-ORD123456.pdf
// Ubah menjadi:
return $pdf->download('Satya-Palapa-' . $booking->order_number . '-' . now()->format('dmY') . '.pdf');
```

## Dependencies

### Composer Packages:
```json
{
    "barryvdh/laravel-dompdf": "^2.2"
}
```

Installed packages:
- `barryvdh/laravel-dompdf` - PDF generation
- `dompdf/dompdf` - PDF library
- Dependencies lainnya (font-lib, svg-lib, css-parser)

### Installation:
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Configuration

### DomPDF Config
Lokasi: `config/dompdf.php`

**Important settings:**
```php
'mode' => '',                       // '' or 'utf-8'
'format' => 'A4',                   // Paper size
'default_font' => 'Segoe UI',       // Font default
'dpi' => 96,                        // Resolution
'enable_php' => false,              // PHP execution
'enable_javascript' => false,       // JavaScript
'enable_remote' => false,           // Remote files
'public_path' => null,              // Custom path
'chroot' => null,                   // Restrict file access
'include_path' => null,             // Include paths
'log_output_file' => storage_path('logs/dompdf.log'),
'tempdir' => sys_get_temp_dir(),    // Temp folder
'font_dir' => storage_path('fonts/'),  // Font folder
'font_cache' => storage_path('fonts/'),
'pdf_backend' => 'CPDF',
'require_tls_for_remote' => true,
'cacert_file_path' => null,
'cacert_file_url' => null,
'http_context' => null,
'custom_font_dir' => '',
'custom_font_data' => [],
'logOutputFile' => null,
'allowedProtocols' => [
    'file://' => ['rules' => []],
    'http://' => ['rules' => []],
    'https://' => ['rules' => []],
]
```

## Testing

### Manual Test

1. **Create Booking:**
   ```
   GET /equipment → Add to cart → /checkout → Create booking
   ```

2. **Approve Booking (Admin):**
   ```
   Admin Panel → Equipment Rental Requests → Approve
   ```

3. **Check Email:**
   - Lihat inbox penyewa
   - Verify PDF attachment terlampir
   - Click download/view link

4. **Download Invoice:**
   ```
   GET /invoices/{id}/download
   GET /invoices/{id}/view
   ```

### Troubleshooting

**Error: "View not found"**
- Pastikan file `resources/views/emails/invoice.blade.php` ada
- Clear view cache: `php artisan view:clear`

**Error: "Class not found"**
- Pastikan import di controller benar
- Run: `composer dump-autoload`

**Email tidak terkirim:**
- Check `.env` MAIL configuration
- Verify SMTP credentials
- Check Laravel logs: `storage/logs/`

**PDF tidak generate:**
- Verify DomPDF installed: `composer show | grep dompdf`
- Check DomPDF config
- Check folder permissions untuk temp files

## Security Considerations

1. **Authorization:**
   - Routes tidak protected (public dapat download)
   - Recommend: Add email verification atau OTP

2. **Data Exposure:**
   - Invoice berisi data pribadi (NPM, telepon, email)
   - Ensure HTTPS connection
   - Log access attempts

3. **File Storage:**
   - PDFs tidak disimpan (generated on-the-fly)
   - Lebih aman dan hemat storage

4. **Email Security:**
   - Gunakan verified email sender
   - Implement rate limiting
   - SPF/DKIM configuration

**Recommended Update:**
```php
// Add middleware protection
Route::middleware('auth.invoice')->group(function () {
    Route::get('/invoices/{booking}/download', [InvoiceController::class, 'download']);
    Route::get('/invoices/{booking}/view', [InvoiceController::class, 'view']);
});
```

## File Structure

```
satpals-project/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── InvoiceController.php          ← PDF generation
│   │       └── Admin/
│   │           └── EquipmentRentalRequestController.php
│   ├── Mail/
│   │   └── BookingApprovedMail.php            ← Email mailable
│   └── Notifications/
│       └── BookingApprovedNotification.php    ← Alternative notification
├── resources/
│   └── views/
│       ├── emails/
│       │   ├── invoice.blade.php              ← Invoice template
│       │   └── booking_approved.blade.php     ← Email template
│       └── public/
│           └── bookings/
│               └── show.blade.php             ← Booking detail page
├── routes/
│   └── web.php                                ← Invoice routes
└── config/
    └── dompdf.php                             ← DomPDF config
```

## Performance Notes

- PDF generation takes ~2-5 seconds first time
- Recommend: Queue emails untuk production
- Cache invoice untuk user yang sama: `Cache::get('invoice_'.$id)`

## Future Enhancements

1. Invoice versioning (auto-update saat ada perubahan)
2. Digital signature pada invoice
3. Invoice history/archive
4. Custom invoice template per customer
5. Bulk invoice generation
6. Invoice numbering system
7. Tax/discount calculation
8. Payment tracking integration

## Support & Debugging

Enable detailed logging:

```php
// In InvoiceController.php
\Log::info('Invoice generated', ['booking_id' => $id, 'email' => $booking->renter_email]);
```

Check logs:
```
storage/logs/laravel.log
```

## Related Documentation

- Laravel Mail: https://laravel.com/docs/mail
- DomPDF: https://github.com/barryvdh/laravel-dompdf
- Blade Templating: https://laravel.com/docs/blade
