# Revisi Kelola Anggota UKM - Dokumentasi

## Ringkasan Perubahan

Revisi kelola anggota UKM Satya Palapa telah selesai dengan empat requirement utama yang diimplementasikan:

### 1. ✅ Auto-Konversi Alumni (Cron Job)
**Status:** Completed  
**Deskripsi:** Sistem akan otomatis mengubah status anggota menjadi alumni ketika periode mereka + 4 tahun  

**File yang berubah:**
- `app/Console/Commands/ConvertExpiredMembersToAlumni.php` (baru)
- `app/Console/Kernel.php` - Scheduled task ditambahkan
- `app/Models/Member.php` - Method `getExpiredMembers()` dan `convertToAlumni()`

**Cara kerja:**
- Command berjalan setiap hari di tengah malam (via scheduler)
- Mengecek member dengan `status = 'aktif'` dan periode <= 4 tahun lalu
- Otomatis update status menjadi `'alumni'`

**Manual Test:**
```bash
php artisan members:convert-expired-to-alumni
```

---

### 2. ✅ Hapus Status "Keluar"
**Status:** Completed  
**Deskripsi:** Status member "Keluar" dihapus dan diganti dengan hanya "Aktif" dan "Alumni"

**File yang berubah:**
- `database/migrations/2026_03_24_000003_modify_members_table.php` (baru)
- `app/Models/Member.php` - Hapus `const STATUS_KELUAR`
- `app/Http/Controllers/Admin/MemberController.php` - Update validation
- `resources/views/admin/members/create.blade.php` - Hapus opsi "Keluar"
- `resources/views/admin/members/edit.blade.php` - Hapus opsi "Keluar"

**Enum Status Terbaru:**
```php
'aktif'  => Aktif (Member aktif)
'alumni' => Alumni (Member alumni)
// 'keluar' dihapus
```

**Migrasi Data:**
- Member yang punya status 'keluar' otomatis dikonversi ke 'alumni'

---

### 3. ✅ Angkatan dari Periode Diklat
**Status:** Completed  
**Deskripsi:** Angkatan member sekarang otomatis diambil dari `tahun_masuk` pada tabel `diklat_periods`

**File yang berubah:**
- `database/migrations/2026_03_24_000003_modify_members_table.php` - Tambah kolom `diklat_period_id`
- `app/Models/Member.php` - Relasi `diklatPeriod()` + update `createFromDiklatRegistration()`
- `app/Http/Controllers/Admin/DiklatController.php` (jika ada)

**Relasi:**
```
Member -> DiklatRegistration -> DiklatPeriod
                                   ↓
                              tahun_masuk = angkatan member
```

**Contoh:**
- Member mendaftar di periode "Diklat 2024" (tahun_masuk: 2024)
- Angkatan member otomatis = 2024
- Setelah 4 tahun (2028), status otomatis → alumni

---

### 4. ✅ Spesifikasi Dinamis (Spesifikasi Lainnya)
**Status:** Completed  
**Deskripsi:** Sekarang bisa menambahkan spesifikasi selain 5 yang standar (drum, keyboard, vocal, bass, guitar)

**File yang berubah:**
- `database/migrations/2026_03_24_000004_add_spesifikasi_lainnya_to_members_table.php` (baru)
- `app/Models/Member.php` - Tambah `spesifikasi_lainnya` ke fillable & casts
- `app/Http/Controllers/Admin/MemberController.php` - Handle parse string → array
- `resources/views/admin/members/create.blade.php` - Form input baru
- `resources/views/admin/members/edit.blade.php` - Form input baru

**Format Data:**
```php
// DB tabel members
spesifikasi: ["drum", "keyboard"]        // Standard (JSON array)
spesifikasi_lainnya: ["Biola", "Kecapi"] // Additional (JSON array)
```

**Form Input:**
- Field text dengan placeholder "Contoh: Biola, Kecapi, atau instrumen lainnya"
- Input dipisahkan dengan koma dan otomatis di-trim

**Proses di Controller:**
```php
// Input string: "Biola, Kecapi, Rebab"
$validated['spesifikasi_lainnya'] = array_map('trim', explode(',', $input));
// Hasil: ["Biola", "Kecapi", "Rebab"]
```

---

## Database Changes

### Migrations Applied:
1. `2026_03_24_000003_modify_members_table.php`
   - Tambah: `diklat_period_id` (foreign key)
   - Ubah: Enum status dari `['aktif', 'alumni', 'keluar']` → `['aktif', 'alumni']`
   - Migrasi: Data 'keluar' → 'alumni'

2. `2026_03_24_000004_add_spesifikasi_lainnya_to_members_table.php`
   - Tambah: `spesifikasi_lainnya` (JSON, nullable)

### Struktur Tabel Members (Updated):
```
members
├── id (primary)
├── diklat_registration_id (foreign)
├── diklat_period_id (foreign) ← NEW
├── nama_lengkap
├── jenis_kelamin
├── no_telepon
├── npm (unique)
├── fakultas
├── prodi
├── spesifikasi (JSON array)
├── spesifikasi_lainnya (JSON array) ← NEW
├── tahun_daftar
├── angkatan
├── status (enum: aktif, alumni)
├── foto
├── created_at
├── updated_at
```

---

## Command & Scheduling

### Console Command
```bash
# File: app/Console/Commands/ConvertExpiredMembersToAlumni.php
# Signature: members:convert-expired-to-alumni

php artisan members:convert-expired-to-alumni
```

### Scheduler (Cron Job)
```php
// File: app/Console/Kernel.php
// Berjalan setiap hari di tengah malam
$schedule->command('members:convert-expired-to-alumni')->daily();

// Untuk server produksi, tambahkan ke crontab:
// * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Testing

### Test Status Conversion
```bash
# Manual jalankan command
php artisan members:convert-expired-to-alumni

# Expected output:
# ✓ Converted: [Nama Anggota] (Angkatan XXXX)
# Successfully converted X members to alumni status.
```

### Test Form
1. Buka: `/admin/members/create`
2. Isi form dengan:
   - Spesifikasi: Pilih drum, keyboard, dll
   - Spesifikasi Lainnya: "Biola, Kecapi, Suling"
3. Submit dan verify di database

### View Data
```php
// Get all members
Member::all();

// Get only active members
Member::where('status', 'aktif')->get();

// Get only alumni
Member::where('status', 'alumni')->get();

// Get members from specific period
Member::whereHas('diklatPeriod', function($q) {
    $q->where('tahun_masuk', 2024);
})->get();
```

---

## API / Relasi Model

### Member Model
```php
// Relasi
$member->diklatRegistration()  // DiklatRegistration
$member->diklatPeriod()        // DiklatPeriod (NEW)
$member->boardPositions()      // BoardMember[]
$member->activeBoardPosition() // BoardMember?

// Methods
Member::getExpiredMembers()    // Get members to convert to alumni
$member->convertToAlumni()     // Convert to alumni status

// Constants
Member::STATUS_AKTIF           // 'aktif'
Member::STATUS_ALUMNI          // 'alumni'
Member::SPESIFIKASI_OPTIONS    // Spec options
```

---

## Notes

- ⚠️ **Important:** Schedule harus berjalan di server untuk auto-conversion bekerja
- Member dengan status 'keluar' lama akan tersimpan dengan status 'alumni'
- Spesifikasi lainnya bersifat optional dan bisa dikosongkan
- Angkatan sekarang read-only saat create dari diklat registration, tapi tetap bisa diedit manual

---

## Summary Progress

| Requirement | Status | Selesai |
|---|---|---|
| 1. Auto-alumni (4+ tahun) | ✅ | Cron job + command |
| 2. Hapus status keluar | ✅ | Enum updated, data migrated |
| 3. Angkatan dari periode | ✅ | FK added, relasi established |
| 4. Spesifikasi lainnya | ✅ | Column added, form updated |

**Semuanya ready untuk deployment!** 🎉
