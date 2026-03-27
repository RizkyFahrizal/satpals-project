# 🔄 Cara Mengubah/Customize Struktur Pengurus

Jika Anda ingin mengubah komposisi pengurus, berikut adalah panduan lengkapnya.

---

## 📋 Opsi 1: Edit Langsung via Admin Panel (Paling Mudah)

### A. Tambah Pengurus Baru
1. Buka: `http://localhost:8000/admin/board`
2. Klik "Tambah Pengurus"
3. Isi form:
   - Cari anggota by nama/NPM
   - Pilih jabatan
   - (Optional) Upload foto
   - (Optional) Create akun login
4. Klik "Simpan"

### B. Edit Pengurus Existing
1. Cari baris pengurus di tabel
2. Klik tombol "Edit" (ikon pensil)
3. Edit:
   - Jabatan (bisa diubah)
   - Foto
   - Status aktif/nonaktif
4. Klik "Simpan Perubahan"

**Note**: Member tidak bisa diubah (hanya jabatan, foto, urutan)

### C. Hapus Pengurus
1. Cari baris pengurus di tabel
2. Klik tombol "Hapus" (ikon trash)
3. Konfirmasi penghapusan
4. Data akan dihapus

### D. Atur Ulang Urutan
1. Di tabel, drag & drop baris pengurus
2. Urutan otomatis tersimpan
3. Refresh untuk lihat perubahan

---

## 📄 Opsi 2: Reset Semua Pengurus (via Artisan)

Jika ingin reset ke data awal, jalankan:

```bash
php artisan db:seed --class=BoardMemberSeeder
```

**Peringatan**: Ini akan menghapus SEMUA pengurus yang sudah ada dan reload dari seeder.

---

## 🔧 Opsi 3: Custom Seeder (Untuk Setup Berbeda)

Jika ingin setup pengurus dengan komposisi berbeda:

### Step 1: Buat Seeder Baru
```bash
php artisan make:seeder CustomBoardMemberSeeder
```

### Step 2: Edit File Seeder
Buka: `database/seeders/CustomBoardMemberSeeder.php`

Ganti struktur board dengan:
```php
$boardStructure = [
    [
        'member_id' => 1,  // ID member dari database
        'jabatan' => 'ketua_umum',
        'periode' => '2026',
        'diklat_period_id' => 1,
        'urutan' => 1,
    ],
    [
        'member_id' => 2,
        'jabatan' => 'subsie_band',
        'periode' => '2026',
        'diklat_period_id' => 1,
        'urutan' => 2,
    ],
    // ... tambah lebih banyak sesuai kebutuhan
];
```

### Step 3: Jalankan Seeder
```bash
php artisan db:seed --class=CustomBoardMemberSeeder
```

---

## 📊 Opsi 4: Direct Database Edit (Advanced)

### Jika ingin edit langsung di database:

```bash
php artisan tinker
```

**Tambah Pengurus:**
```php
\App\Models\BoardMember::create([
    'member_id' => 1,
    'jabatan' => 'ketua_umum',
    'periode' => '2026',
    'diklat_period_id' => 1,
    'urutan' => 1,
    'tanggal_buka' => now(),
    'tanggal_tutup' => now()->addMonths(6),
]);
```

**Update Pengurus:**
```php
\App\Models\BoardMember::find(1)->update([
    'jabatan' => 'wakil_ketua_umum',
]);
```

**Hapus Pengurus:**
```php
\App\Models\BoardMember::find(1)->delete();
```

**Lihat Semua Pengurus:**
```php
\App\Models\BoardMember::with('member')->get();
```

---

## 🎯 Opsi 5: Migrasi dari Struktur Lama

Jika ada data pengurus dari sistem lama:

### Step 1: Export Data Lama
- Ambil data dari Excel/CSV
- Format: member_id, jabatan, periode

### Step 2: Import ke Database
Buat script PHP di tinker:
```php
$data = [
    ['member_id' => 1, 'jabatan' => 'ketua_umum'],
    ['member_id' => 2, 'jabatan' => 'wakil_ketua_umum'],
    // ... dst
];

foreach ($data as $item) {
    \App\Models\BoardMember::create([
        'member_id' => $item['member_id'],
        'jabatan' => $item['jabatan'],
        'periode' => '2026',
        'diklat_period_id' => 1,
        'urutan' => 0,
    ]);
}
```

---

## 📝 Referensi Data

### Member ID Reference
Cek member yang tersedia:
```bash
php artisan tinker
```

```php
\App\Models\Member::where('status', 'aktif')
    ->get(['id', 'nama_lengkap', 'npm'])
    ->toArray();
```

### Jabatan Options
```php
\App\Models\BoardMember::JABATAN_OPTIONS
```

Output:
```php
[
    'ketua_umum' => 'Ketua Umum',
    'wakil_ketua_umum' => 'Wakil Ketua Umum',
    'sekretaris' => 'Sekretaris',
    'bendahara' => 'Bendahara',
    'mpa' => 'Ketua Majelis Perwakilan Anggota',
    'subsie_band' => 'Subsie Band',
    'subsie_peralatan' => 'Subsie Peralatan',
    'subsie_humas' => 'Subsie Humas',
    'subsie_pdd' => 'Subsie Produksi dan Dokumentasi',
    'subsie_kesekretariatan' => 'Subsie Kesekretariatan',
]
```

### DiklatPeriod ID Reference
```php
\App\Models\DiklatPeriod::get(['id', 'nama_periode', 'tahun_masuk'])->toArray();
```

---

## ✅ Checklist Sebelum Deploy

- [ ] Semua 18 pengurus sudah ter-assign
- [ ] BPH memiliki 5 orang (Ketua, Wakil, Sekretaris, Bendahara, MPA)
- [ ] Setiap Subsie memiliki minimal 2 orang
- [ ] Tidak ada duplikasi posisi per periode
- [ ] Foto sudah di-upload untuk setiap pengurus (opsional tapi direkomendasikan)
- [ ] Testing di halaman publik `/struktur` untuk lihat hasilnya
- [ ] Testing di admin `/admin/board` untuk lihat editing

---

## 🐛 Troubleshooting

### Member tidak muncul di search
→ Pastikan member status = 'aktif'

### Tidak bisa tambah pengurus
→ Cek apakah member sudah punya posisi di periode tersebut

### Foto tidak terupload
→ Cek file size (max 2MB) dan format (JPG/PNG/WebP)

### Data tidak tersimpan
→ Check console browser atau log di `storage/logs/laravel.log`

---

## 📞 Bantuan

Untuk info lebih lengkap, lihat:
- **Admin Guide**: `STRUKTUR_PENGURUS_GUIDE.md`
- **Data Reference**: `STRUKTUR_PENGURUS_DATA.md`
- **Controller**: `app/Http/Controllers/Admin/BoardMemberController.php`
- **Model**: `app/Models/BoardMember.php`
