# 📋 Panduan Kelola Struktur Pengurus

## 🎯 Overview
Struktur pengurus UKM Satya Palapa terdiri dari Badan Pengurus Harian (BPH) dan 5 Sub Seksi (Subsie).

---

## 📊 Akses Admin Panel

### URL
```
http://localhost:8000/admin/board
```

### Fitur Utama

#### 1️⃣ **Lihat Daftar Pengurus**
- Filter berdasarkan periode
- Lihat urutan pengurus
- Lihat jabatan dan nama lengkap
- Toggle status aktif/nonaktif

#### 2️⃣ **Tambah Pengurus Baru**
- Klik tombol **"Tambah Pengurus"**
- Form akan membuka modal

**Form Fields:**
1. **Pilih Anggota** (Required)
   - Search real-time berdasarkan nama atau NPM
   - Hanya menampilkan anggota aktif yang belum punya posisi di periode tersebut
   - Contoh: Ketikkan "hylmi" atau "24032010028"

2. **Periode Diklat** (Optional)
   - Pilih periode untuk auto-assign tanggal dibuka/ditutup
   - Jika dipilih, `tanggal_buka` dan `tanggal_tutup` otomatis terisi dari DiklatPeriod

3. **Jabatan** (Required)
   - Badan Pengurus Harian:
     - Ketua Umum
     - Wakil Ketua Umum
     - Sekretaris
     - Bendahara
     - Ketua MPA
   - Sub Seksi:
     - Band (Musik)
     - Peralatan
     - Humas (PR)
     - PDD (Produksi & Dokumentasi)
     - Kesekretariatan

4. **Foto Pengurus** (Optional)
   - Upload JPG, PNG, atau WebP
   - Max 2MB
   - Preview sebelum save

5. **Buat Akun Login** (Optional)
   - Jika dicentang, pengurus dapat login ke admin panel
   - Username auto-generate dari nama
   - Password di-generate dan akan ditampilkan

#### 3️⃣ **Edit Pengurus**
- Klik tombol **Edit** (ikon pensil) di setiap baris
- **Catatan**: Member tidak bisa diubah (hanya jabatan, foto, status)
- Simpan perubahan

#### 4️⃣ **Hapus Pengurus**
- Klik tombol **Hapus** (ikon trash)
- Konfirmasi penghapusan
- Data akan dihapus dari database

#### 5️⃣ **Atur Urutan Pengurus**
- Drag & drop baris untuk mengatur urutan
- Urutan akan tersimpan otomatis
- Mempengaruhi display di halaman publik

---

## 👁️ Akses Public View

### URL
```
http://localhost:8000/struktur
```

### Tampilan

#### 🎨 Hero Section
- Gradient background kuning-orange
- Judul "Struktur Kepengurusan"
- Periode selector untuk filter

#### 👑 Badan Pengurus Harian
- Tampilkan 5 posisi BPH dalam satu baris
- Kartu dengan foto, nama, prodi
- Badge warna gradient untuk jabatan
- Hover effect: scale dan shadow

#### 🎯 Sub Seksi (Subsie)
- Organized by subsie dengan header berwarna
- Setiap subsie punya warna unik:
  - 🎸 Band: Red gradient
  - 🔧 Peralatan: Blue gradient
  - 📢 Humas: Green gradient
  - 📷 PDD: Purple gradient
  - 📝 Kesekretariatan: Amber gradient

#### 📝 Fitur Text Ellipsis
- Nama panjang akan di-truncate dengan "..."
- `line-clamp-2` untuk nama (max 2 baris)
- `line-clamp-1` untuk prodi
- Kartu size konsisten tidak mengecil/membesar

---

## 🔍 Searchable Member Select - Detail

### Cara Kerja
1. Ketik di input "Cari anggota (nama/npm)..."
2. System akan search ke database:
   - Nama lengkap (LIKE)
   - NPM (LIKE)
3. Exclude member yang sudah punya posisi di periode tersebut
4. Tampilkan max 10 hasil

### Response Format
```json
[
  {
    "id": 1,
    "text": "Hylmi Tri Widiastoro (24032010028) - Teknik Industri",
    "nama_lengkap": "Hylmi Tri Widiastoro",
    "npm": "24032010028"
  }
]
```

### JavaScript Implementation
```javascript
// Event: Input change dengan debounce 300ms
// Fetch: GET /admin/board/search-members?search=...&periode=...
// Display: Clickable results dengan nama lengkap dan NPM
// Select: Auto-populate member_id saat diklik
```

---

## ⚙️ Data Model

### BoardMember Fields
```
id              - Primary Key
member_id       - FK to members table (Required)
jabatan         - Position code (Required)
periode         - Organization period (e.g., "2026")
diklat_period_id - FK to diklat_periods (Optional)
tanggal_buka    - Start date (auto-filled dari DiklatPeriod)
tanggal_tutup   - End date (auto-filled dari DiklatPeriod)
foto            - Photo path (Optional)
urutan          - Order/sequence (for sorting)
is_active       - Status flag (boolean)
created_at      - Timestamp
updated_at      - Timestamp
```

### Relationships
```
- belongsTo(Member)
- belongsTo(DiklatPeriod)
- hasOne(User) - jika ada account login
```

---

## 🚀 API Endpoints

### 1. Get Board Members List
```
GET /admin/board
```

### 2. Search Members (Real-time)
```
GET /admin/board/search-members?search=:query&periode=:periode
```
Response: JSON array of members

### 3. Create Board Member
```
POST /admin/board
Content-Type: multipart/form-data

Params:
- member_id (required)
- jabatan (required)
- diklat_period_id (optional)
- foto (file, optional)
- create_account (checkbox, optional)
```

### 4. Update Board Member
```
POST /admin/board/:id
Method: PUT or POST with _method=PUT

Params:
- jabatan (required)
- diklat_period_id (optional)
- foto (file, optional)
- hapus_foto (checkbox, optional)
```

### 5. Delete Board Member
```
DELETE /admin/board/:id
```

### 6. Reorder Board Members
```
POST /admin/board/reorder
Content-Type: application/json

Body:
{
  "orders": [
    {"id": 1, "urutan": 1},
    {"id": 2, "urutan": 2}
  ]
}
```

---

## 📝 Tips & Tricks

### ✅ Best Practices
1. **Organize by Spesifikasi**: Tempatkan member sesuai spesialisasi musik mereka
   - Vocalist untuk posisi depan
   - Gitarist untuk subsie dengan banyak member
   - Drummer/Bassist untuk rhythm section

2. **Balanced Team**: Pastikan setiap subsie punya anggota yang balanced
   - Tidak semua gitarist di satu subsie
   - Mix vocalist dan instrumentalis

3. **Leadership**: Pilih member dengan leadership untuk BPH
   - Pertimbangkan senior/experience
   - Mix gender untuk balanced representation

4. **Documentation**: Selalu upload foto untuk konsistensi visual
   - Recommended: Passport photo
   - Size: 400x500px
   - Format: JPG atau PNG

### ⚠️ Catatan Penting
- Member tidak bisa dipindahkan ke subsie berbeda (hanya hapus & tambah baru)
- Duplikasi posisi di periode sama dicegah otomatis
- Status nonaktif member akan auto-exclude dari search
- Foto otomatis di-resize dan di-optimize

---

## 🔄 Workflow Contoh

### Scenario: Tambah Pengurus Baru ke Subsie Band

1. Buka `/admin/board`
2. Klik "Tambah Pengurus"
3. Di form:
   - Search "CHOIRUL" → Pilih hasil
   - Periode: "Periode Diklat Angkatan 2023"
   - Jabatan: "Subsie Band"
   - Foto: Upload foto drummer
   - Create Account: Check (opsional)
4. Klik "Simpan"
5. Lihat di halaman publik `/struktur` → Subsie Band

---

## 📞 Troubleshooting

| Masalah | Solusi |
|---|---|
| Member tidak muncul di search | Pastikan status member "aktif" |
| Tidak bisa pilih member | Member sudah punya posisi di periode tersebut |
| Foto tidak terupload | Check file size max 2MB |
| Perubahan tidak tersimpan | Check browser console untuk error |
| Urutan pengurus berubah | Refresh page atau atur ulang |

---

## 📞 Support

Untuk pertanyaan atau issue:
1. Check database dengan `php artisan tinker`
2. Review BoardMemberController::class untuk logic
3. Check view di `resources/views/admin/board/index.blade.php`
4. Jalankan `php artisan db:seed --class=BoardMemberSeeder` untuk reset data
