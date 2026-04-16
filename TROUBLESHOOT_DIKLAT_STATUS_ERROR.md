# Solusi Error Ubah Status Pendaftar Diklat

## Error yang Terjadi

Ketika mengubah status pendaftar diklat menjadi "Diterima", ada 2 error:

### Error 1: Unknown column 'no_telepon_ortu' in 'field list'
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'no_telepon_ortu' in 'field list'
App\Models\Member::createFromDiklatRegistration (139)
```

**Penyebab:** Kolom tidak ada di database karena migration belum dijalankan atau opcode cache tidak ter-refresh.

### Error 2: The GET method is not supported 
```
The GET method is not supported for route admin/diklat/163/status
```

**Penyebab:** Sistem mencoba mengakses route dengan GET padahal route hanya support PATCH.

---

## Solusi (SUDAH DITERAPKAN)

### 1. Migration
Migration `2026_04_15_000001_add_health_info_to_members_table` telah ditambahkan untuk:
- Tambah kolom `no_telepon_ortu` (varchar)
- Tambah kolom `riwayat_penyakit` (text)  
- Tambah kolom `riwayat_alergi` (text)

**Status:** ✅ Sudah dijalankan (Batch 2)

### 2. Refresh Autoloader & Cache
Jalankan commands:
```bash
php artisan cache:clear
php artisan config:cache
php artisan view:clear
composer dumpautoload
```

**Status:** ✅ Sudah dijalankan

### 3. Verify Kolom di Database
Kolom-kolom sudah tersimpan:
- ✅ `no_telepon_ortu` (varchar(255))
- ✅ `riwayat_penyakit` (text)
- ✅ `riwayat_alergi` (text)

---

## Testing Langkah-Langkah

1. **Buat atau cari pendaftar diklat dengan status "Menunggu"**
   - Pergi ke: `/admin/diklat`
   - Cari pendaftar dengan status "⏳ Menunggu"

2. **Ubah status menjadi "Diterima"**
   - Klik dropdown status pendaftar
   - Pilih "✅ Diterima"
   - Form otomatis submit dengan method PATCH

3. **Verifikasi Success**
   - Seharusnya muncul pesan: "Status pendaftaran berhasil diubah menjadi diterima. Data anggota berhasil ditambahkan."
   - Status berubah menjadi hijau "✅ Diterima"

4. **Verifikasi Member Dibuat**
   - Pergi ke: `/admin/members`
   - Cari member dengan NPM yang sama
   - Verifikasi data kesehatan sudah terisi dari diklat registration

---

## Jika Masih Ada Error

**Opsi 1: Restart PHP-FPM atau Server**
```bash
# Jika menggunakan Laravel Artisan serve
# Stop dengan Ctrl+C dan jalankan lagi:
php artisan serve
```

**Opsi 2: Clear Seluruh Cache**
```bash
php artisan optimize:clear
composer dumpautoload -o
```

**Opsi 3: Check Event Listener**
- Pastikan tidak ada middleware atau event listener yang interfere dengan request
- Check file `config/app.php` - pastikan service providers registered

---

## Route Configuration

Route untuk ubah status sudah benar di `routes/web.php`:
```php
Route::patch('/diklat/{registration}/status', [DiklatRegistrationController::class, 'updateStatus'])->name('diklat.update-status');
```

View form di `resources/views/admin/diklat/index.blade.php`:
```php
<form action="{{ route('admin.diklat.update-status', $reg) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <select name="status" onchange="this.form.submit()">
        <!-- options -->
    </select>
</form>
```

**Status:** ✅ Sudah benar

---

## Model Method

Method `createFromDiklatRegistration()` di `app/Models/Member.php` sudah update untuk mengambil 3 kolom baru:
```php
public static function createFromDiklatRegistration(DiklatRegistration $registration): self
{
    return self::create([
        // ... existing fields ...
        'no_telepon_ortu' => $registration->no_telepon_ortu,
        'riwayat_penyakit' => $registration->riwayat_penyakit,
        'riwayat_alergi' => $registration->riwayat_alergi,
        // ... existing fields ...
    ]);
}
```

**Status:** ✅ Sudah update

---

## Next Steps

1. Refresh browser (Ctrl+F5 untuk hard refresh)
2. Coba ubah status pendaftar diklat lagi
3. Jika masih error, check Laravel error logs di `storage/logs/`
