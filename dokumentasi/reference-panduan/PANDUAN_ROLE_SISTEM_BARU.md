# 📖 Panduan Penggunaan Sistem Role Baru

## 🎯 Ringkasan Singkat

Role pengurus sekarang **spesifik sesuai jabatan**, bukan generic "pengurus". 

Contoh:
- Ketua Umum mendapat role `ketua_umum`
- Bendahara mendapat role `bendahara`
- Subsie Band mendapat role `band`
- dst...

---

## 🔑 Role yang Tersedia

### Badan Pengurus Harian
| Role | Nama | Keterangan |
|------|------|-----------|
| `ketua_umum` | Ketua Umum | Posisi tertinggi, hanya admin yg bisa tambah |
| `wakil_ketua_umum` | Wakil Ketua Umum | Pemimpin kedua |
| `bendahara` | Bendahara | Mengelola keuangan |
| `sekretaris` | Sekretaris | Mengelola administrasi |
| `mpa` | Ketua MPA | Ketua Majelis Perwakilan Anggota |

### Sub Seksi
| Role | Nama | Keterangan |
|------|------|-----------|
| `band` | Subsie Band | Mengelola divisi band musik |
| `peralatan` | Subsie Peralatan | Mengelola divisi peralatan |
| `humas` | Subsie Humas | Public Relations & Media |
| `pdd` | Subsie PDD | Produksi & Dokumentasi |
| `kesekretariatan` | Subsie Kesekretariatan | Support administrasi |

### Role Lainnya
- `super_admin` - Super Admin (tidak berubah)
- `public` - User Biasa / Pengunjung (tidak berubah)

---

## 📋 Cara Kerja

### Scenario 1: Menambah Pengurus Baru via Menu Struktur Pengurus

**Alur:**
1. Buka `/admin/board` → "Struktur Pengurus"
2. Klik "Tambah Pengurus Baru"
3. Pilih anggota dari dropdown
4. **Pilih Jabatan** (contoh: "Bendahara")
5. ✅ **AUTOMATIC**: Jika klik "Buat Akun Login", role user otomatis jadi `bendahara`

**Output:**
- User account dibuat dengan role = `bendahara` (sesuai jabatan)
- User bisa login ke admin panel dengan akses sesuai role

### Scenario 2: Tambah Ketua Umum

**Alur:**
1. Buka `/admin/users` → "Kelola User"
2. Klik "Tambah User Baru"
3. Isi nama, email, password
4. **Pilih Role** dari dropdown → `Ketua Umum` ada di sini
5. Simpan

**Note:**
- Di menu Struktur Pengurus, `Ketua Umum` TIDAK ada di dropdown jabatan
- Hanya Super Admin yang bisa create role ini dari menu Admin Users

### Scenario 3: Edit Role User

**Alur:**
1. Buka `/admin/users` → "Kelola User"
2. Klik "Edit" user yang ingin diubah
3. Scroll ke bagian "Pilih Role"
4. Pilih role baru
5. Simpan

**Perubahan:** Role user langsung berubah, akses admin menyesuaikan

---

## ⚙️ Fitur Otomatis

### Saat Membuat Login dari Struktur Pengurus

```
Anggota: Budi Santoso
Jabatan: Bendahara
✅ Buat Akun Login
    ↓ (automatic)
Role: bendahara (NOT 'pengurus')
Email: budisantoso@satpals.com
Password: satpals123
```

**Keuntungan:**
- Tidak perlu manual assign role di menu Admin
- Role selalu sesuai dengan jabatan pengurus
- Lebih konsisten & aman

---

## 🔒 Pembatasan & Rules

| Fitur | Super Admin | Ketua Umum | Subsie | Public |
|-------|-----------|-----------|--------|--------|
| Akses Admin Panel | ✅ | ✅ | ✅ | ❌ |
| Buat Pengurus (Struktur) | ❌ | ✅ | ✅ | ❌ |
| Tambah Ketua Umum | ✅ | ❌ | ❌ | ❌ |
| Edit Data Pengurus | ✅ | ✅ | ✅ | ❌ |
| Kelola Keuangan | ✅ | ✅ | ✅ | ❌ |
| View Public Data | ✅ | ✅ | ✅ | ✅ |

---

## ⚠️ Hal-Hal Penting

1. **Ketua Umum Spesial**
   - Role `ketua_umum` HANYA bisa dibuat dari menu Admin Users
   - Saat tambah pengurus di menu Struktur Pengurus, role ini tidak tersedia
   - Hanya Super Admin yang punya akses penuh

2. **Login User**
   - Setiap pengurus bisa login ke admin dengan role mereka
   - Role menentukan akses menu apa saja yang bisa dilihat
   - Password default: `satpals123` (harus di-reset saat login pertama)

3. **Perubahan Role**
   - Super Admin bisa mengubah role user kapan saja di menu Admin Users
   - Role otomatis berubah sesuai perubahan jabatan jika di-update di Struktur Pengurus

---

## 🔍 Cara Cek Role User

### Via Dashboard
1. Buka `/admin` → "Kelola User"
2. Cari user di tabel
3. Kolom "Role" menunjukkan role user saat ini

### Via Database (Advanced)
```sql
SELECT id, name, email, role FROM users WHERE role LIKE '%subsie_%' OR role IN ('ketua_umum', 'bendahara');
```

---

## 📱 Contoh Role Labels di UI

User akan melihat role mereka di berbagai tempat:

```
👤 Super Admin
👤 Ketua Umum
👤 Bendahara
👤 Subsie Band
👤 Subsie Peralatan
dst...
```

---

## 💡 Tips

1. **Untuk Struktur Pengurus yang Kompleks**
   - Pastikan semua anggota sudah terdaftar di menu "Kelola Anggota" dulu
   - Baru kemudian assign ke jabatan di menu "Struktur Pengurus"

2. **Untuk Maksimalkan Fitur**
   - Gunakan role spesifik untuk permission management di masa depan
   - Contoh: hanya Bendahara yang bisa edit data keuangan

3. **Maintenance**
   - Backup user list secara berkala
   - Review role assignments setiap periode berubah

---

## 🆘 Troubleshooting

| Problem | Solution |
|---------|----------|
| User tidak bisa login | Cek role user di Admin Users, pastikan `is_active = true` |
| Ketua Umum tidak ada di dropdown | Itu benar! Hanya super admin bisa buat. Gunakan menu Admin Users |
| Role user tidak berubah | Refresh page, atau check di database direct |
| Lupa password user | Super Admin bisa reset di menu Admin Users |

---

## 📞 Kontak Support

Jika ada pertanyaan atau masalah:
1. Hubungi Super Admin
2. Check documentation ini
3. Lihat log file di `storage/logs/laravel.log`

