# 📊 Perbedaan Kolom "Jabatan" vs "Divisi" di Board Members

## 🔍 Quick Comparison

| Aspek | **Jabatan** | **Divisi** |
|-------|-----------|----------|
| **Tujuan** | Posisi/peran dalam struktur | Departemen/bidang kerja |
| **Contoh** | Ketua, Wakil, Sekretaris | Band, Humas, Peralatan |
| **Status** | REQUIRED (wajib diisi) | OPTIONAL (boleh kosong) |
| **Tipe Data** | String | String (nullable) |
| **Usage** | Hierarchy & permission | Departemen grouping |
| **DB Column** | `jabatan` (NOT NULL) | `divisi` (NULLABLE) |

---

## 📝 Penjelasan Detail

### 1️⃣ **JABATAN** (Position/Role)

**Definisi**: Posisi atau peran yang dipegang pengurus dalam struktur organisasi.

**Contoh Jabatan:**
```
BPH (Badan Pengurus Harian):
  ├─ Ketua Umum
  ├─ Wakil Ketua Umum
  ├─ Sekretaris
  ├─ Bendahara
  └─ Ketua MPA

Subsie (Sub Seksi):
  ├─ Subsie Band
  ├─ Subsie Peralatan
  ├─ Subsie Humas
  ├─ Subsie PDD
  └─ Subsie Kesekretariatan
```

**Database:**
```sql
jabatan VARCHAR(255) NOT NULL
```

**Code Reference:**
```php
// Di BoardMember.php
const JABATAN_OPTIONS = [
    'ketua_umum' => 'Ketua Umum',
    'wakil_ketua_umum' => 'Wakil Ketua Umum',
    'sekretaris' => 'Sekretaris',
    'bendahara' => 'Bendahara',
    'mpa' => 'Ketua MPA',
    'subsie_band' => 'Subsie Band',
    'subsie_peralatan' => 'Subsie Peralatan',
    'subsie_humas' => 'Subsie Humas',
    'subsie_pdd' => 'Subsie PDD',
    'subsie_kesekretariatan' => 'Subsie Kesekretariatan',
];
```

---

### 2️⃣ **DIVISI** (Department/Section)

**Definisi**: Departemen atau bagian yang lebih besar yang bisa mengelompokkan beberapa jabatan.

**Contoh Divisi:**
```
Divisi Musik
  ├─ Ketua Divisi
  ├─ Wakil Ketua Divisi
  └─ Anggota Divisi

Divisi Humas
  ├─ Kepala Humas
  └─ Anggota Humas

Divisi Peralatan
  ├─ Ketua Peralatan
  └─ Anggota Peralatan
```

**Database:**
```sql
divisi VARCHAR(255) NULLABLE
```

**Status**: Optional/boleh kosong (NULL)

---

## 🎯 Kapan Pakai Mana?

### ✅ **JABATAN** - SELALU DIISI

**Wajib diisi** untuk setiap board member karena:
- Menentukan peran/posisi dalam struktur
- Menentukan hierarchy (BPH vs Subsie)
- Digunakan untuk permission/access control
- Ditampilkan di public view

**Contoh:**
```
Hylmi Tri Widiastoro → jabatan = "ketua_umum"
Mirza Ramadian Raffa → jabatan = "wakil_ketua_umum"
CHOIRUL WAHYU ADJI → jabatan = "subsie_band"
```

---

### ⚠️ **DIVISI** - OPSIONAL (Boleh Kosong)

**Boleh dikosongkan** karena:
- Fitur tambahan untuk grouping
- Tidak semua organisasi perlu divisi
- Saat ini tidak digunakan di project
- Untuk future enhancement

**Kalau ingin pakai:**
```
Hylmi Tri Widiastoro:
  - jabatan: "ketua_umum"
  - divisi: "Organisasi Umum"

CHOIRUL WAHYU ADJI:
  - jabatan: "subsie_band"
  - divisi: "Musik"

Isna Nur Rahmawati:
  - jabatan: "sekretaris"
  - divisi: "Administrasi"
```

---

## 📊 Current Data Status

**Di Database saat ini:**
```sql
SELECT COUNT(*) FROM board_members WHERE divisi IS NOT NULL;
-- Result: 0 (semua NULL/kosong)

SELECT DISTINCT jabatan FROM board_members;
-- Result: Sudah terisi 10 jabatan berbeda
```

Artinya: **Kolom `divisi` BELUM DIGUNAKAN**, semua NULL/kosong.

---

## 💡 Use Case: Kapan Perlu Divisi?

### Scenario 1: Organisasi Besar
Jika UKM punya struktur:
```
Struktur Organisasi:
├─ Divisi Musik
│  ├─ Ketua Divisi Musik
│  ├─ Wakil Ketua
│  └─ Anggota
├─ Divisi Humas
│  ├─ Ketua Divisi Humas
│  └─ Anggota
└─ Divisi Administrasi
   ├─ Sekretaris
   └─ Bendahara
```

Maka bisa pakai kolom `divisi`:
```php
[
    ['jabatan' => 'ketua_divisi', 'divisi' => 'Musik'],
    ['jabatan' => 'ketua_divisi', 'divisi' => 'Humas'],
    ['jabatan' => 'anggota', 'divisi' => 'Musik'],
]
```

### Scenario 2: Organisasi Sederhana (Current)
Jika hanya ada BPH dan Subsie:
```
BPH (5 orang):
  ├─ Ketua Umum
  ├─ Wakil Ketua
  ├─ Sekretaris
  ├─ Bendahara
  └─ MPA

Subsie (13 orang):
  ├─ Band (4)
  ├─ Peralatan (3)
  ├─ Humas (2)
  ├─ PDD (2)
  └─ Kesekretariatan (2)
```

→ **TIDAK PERLU `divisi`**, kolom `jabatan` sudah cukup!

---

## 🔧 How to Use (If Needed)

### Filling Divisi via Admin Panel:
1. Buka `/admin/board`
2. Edit pengurus
3. Tambah field "Divisi" (jika sudah di-implement di form)
4. Save

### Via Database:
```bash
php artisan tinker
```

```php
$board = \App\Models\BoardMember::find(1);
$board->update(['divisi' => 'Musik']);
```

### Via Migration (jika ingin setup awal):
```php
// In migration file:
DB::table('board_members')->update([
    'divisi' => case when jabatan like '%band%' then 'Musik'
                    when jabatan like '%humas%' then 'Humas'
                    ... end
]);
```

---

## 📋 Kolom Lengkap Board Members

```php
'id'                 // Primary Key
'member_id'          // FK ke members table
'diklat_period_id'   // FK ke diklat_periods table
'user_id'            // FK ke users table
'jabatan'            // ⭐ REQUIRED - Position (Ketua, Subsie Band, dll)
'divisi'             // ⭐ OPTIONAL - Department (Musik, Humas, dll)
'periode'            // Period tahun (e.g., "2026")
'tanggal_buka'       // Start date
'tanggal_tutup'      // End date
'is_active'          // Active status
'urutan'             // Sort order
'foto'               // Photo path
'created_at'         // Created timestamp
'updated_at'         // Updated timestamp
```

---

## 🎓 Kesimpulan

| Kolom | Status | Fungsi | Contoh |
|-------|--------|--------|--------|
| **jabatan** | ✅ REQUIRED | Posisi dalam struktur | ketua_umum, subsie_band |
| **divisi** | ⚠️ OPTIONAL | Grouping departemen | Musik, Humas, Admin |

**Saat ini:**
- `jabatan` → **DIGUNAKAN** untuk display struktur
- `divisi` → **TIDAK DIGUNAKAN** (semua NULL)

**Bisa digunakan di masa depan** jika:
- Struktur organisasi menjadi lebih kompleks
- Perlu grouping yang lebih detail
- Ada requirement untuk "Divisi Musik", "Divisi Humas", dll.

---

## 📞 Technical Notes

### Model Relationship:
```php
// Di BoardMember.php
public function member() { 
    return $this->belongsTo(Member::class); 
}
```

### Validation:
```php
// Saat create/update
'jabatan' => 'required|in:ketua_umum,wakil_ketua_umum,...',
'divisi' => 'nullable|string|max:255', // Optional
```

### Display (Public View):
```blade
<!-- Hanya tampilkan jabatan (divisi tidak ditampilkan) -->
<span>{{ $member->jabatan_label }}</span>
```

Kolom `divisi` adalah **"reserved for future use"** 🚀
