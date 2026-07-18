# 📋 DEMO SCENARIO PRESENTASI SEMHAS - SATPALS
## Durasi: 15 Menit dengan Penjelasan

---

## 📌 RINGKASAN SINGKAT (2 menit)

### Apa itu SATPALS?
**SATPALS** = Satya Palapa Organization Management System
- Aplikasi web untuk manajemen UKM (Satya Palapa - kelompok kesenian UPN Veteran Jawa Timur)
- Menyelesaikan masalah booking studio, sewa band, sewa alat, dan manajemen keuangan dalam satu platform
- Fitur admin panel + public side

**Masalah yang diselesaikan:**
- ❌ Dulu: booking manual lewat chat/wa, susah tracking
- ✅ Sekarang: online system dengan approval workflow otomatis

---

## 🎯 FITUR WAJIB DIDEMO (8 menit)

> **Tujuan:** Menunjukkan value proposition sistem dan workflow core

### 1️⃣ PUBLIC SIDE: Booking Studio (2 menit)
**Alasan wajib:** Fitur utama yang paling banyak digunakan customer

**Langkah-langkah:**
```
1. Buka halaman public: /studio-bookings/create
2. Jelaskan: "Ini halaman booking studio, customer bisa langsung isi online"
   
3. Isi form dengan data:
   - Nama Penyewa: "Andi Booking Test"
   - Email: "andi@test.com"
   - No HP: "08123456789"
   - Kategori: Pilih "UKM Semua" (tampilkan perbaikan bug validation)
   - Jumlah UKM: 5
   - Jumlah Non-UKM: ~ (auto disable karena UKM Semua dipilih)
   - Tanggal Mulai: Pilih tanggal 3 hari ke depan
   - Durasi: 2 jam
   - Catatan: "Untuk acara kami"

4. Klik "Submit Booking"

5. Tampilkan: "Booking berhasil! Tunggu approval dari admin"
```

**Yang dijelas kepada penonton:**
- "Sistem otomatis validasi input dan prevent user error (show non-UKM field disabled)"
- "Customer dapat konfirmasi booking langsung di screen"
- "Admin akan review dalam 1x24 jam"

---

### 2️⃣ ADMIN SIDE: Login & Dashboard (1 menit)
**Alasan wajib:** Menunjukkan "command center" admin

**Langkah-langkah:**
```
1. Login admin:
   - Email: admin@test.com  (atau sesuai credential testing)
   - Password: password
   
2. Tampilkan dashboard:
   - Total statistik: Booking, Sewa Band, Sewa Alat, Keuangan
   - "Menunggu Approval" counter (TUNJUKKAN YANG SUDAH DIPERBAIKI - 
     include income + expense, bukan cuma expense)
   - Chart/grafik aktivitas bulanan
   
3. Jelaskan: "Admin bisa lihat ringkasan semua aktivitas dalam satu dashboard"
```

**Yang dijelas:**
- "Dashboard real-time menampilkan pending approvals"
- "Bisa langsung lihat berapa pendapatan dan pengeluaran"

---

### 3️⃣ ADMIN SIDE: Approve/Reject Booking Studio (2 menit)
**Alasan wajib:** Menunjukkan workflow dan automation

**Langkah-langkah:**
```
1. Di admin panel, buka: Bookings > Studio Bookings
   
2. Cari booking yang baru dibuat tadi (atau cari dari test data)
   
3. Tampilkan detail booking:
   - Status: "Pending"
   - Booking code
   - Renter info
   - Tanggal & durasi
   
4. Klik "Approve":
   - (Optional) Masukkan catatan approval
   - Klik "Konfirmasi"
   
5. Tampilkan hasil:
   - Status berubah: "Pending" → "Approved"
   - Invoice PDF otomatis generated dan dapat didownload
   - Email notifikasi otomatis terkirim ke penyewa
   - Keuangan otomatis update (pemasukan bertambah)
   
6. (PENTING) Jelaskan automation:
   - ✅ Email notifikasi dikirim otomatis
   - ✅ Invoice PDF generated otomatis  
   - ✅ Pemasukan tercatat otomatis di keuangan
```

**Yang dijelas:**
- "Sistem otomatis mengurangi beban admin paperwork"
- "Customer langsung terima notifikasi approval + invoice"
- "No manual input ke keuangan - semuanya terintegrasi"

---

### 4️⃣ ADMIN SIDE: Lihat Keuangan (1.5 menit)
**Alasan wajib:** Menunjukkan fitur inti: financial tracking

**Langkah-langkah:**
```
1. Buka: Keuangan > Dashboard
   
2. Tampilkan:
   - Statistik: Total Pemasukan, Total Pengeluaran, Saldo
   - Filter berdasarkan:
     * Periode (bulan/tahun)
     * Tipe (Pemasukan/Pengeluaran)
     * Status (Pending/Approved/Rejected)
     
3. Cari/Filter untuk tampilkan booking yang tadi kita approve:
   - Filter by periode bulan ini
   - Filter by Status: "Approved"
   - Akan muncul transaksi dari booking studio yang baru di-approve
   
4. Tampilkan kolom:
   - Tanggal
   - Deskripsi (dari booking)
   - Nominal (dari harga booking)
   - Tipe (Pemasukan)
   - Status
   - Dibuat oleh (audit trail)
   
5. Jelaskan: "Setiap booking yang approved = income auto terecord.
   Admin bisa filter, search, dan track semua transaksi dengan mudah"
```

**Yang dijelas:**
- "Financial data terpusat dalam satu sistem"
- "Mudah tracking untuk laporan keuangan bulanan"
- "Audit trail: siapa yang create transaksi (untuk akuntabilitas)"

---

### 5️⃣ ADMIN SIDE: Manajemen Pengguna & Search Role (1.5 menit)
**Alasan wajib:** Menunjukkan fitur access control & fix yang dilakukan

**Langkah-langkah:**
```
1. Buka: Admin > Manajemen Pengguna
   
2. Tampilkan list pengguna dengan kolom:
   - Nama
   - Email
   - Role
   - Status
   
3. TUNJUKKAN FITUR SEARCH (yang sudah diperbaiki):
   - Coba search: "Ketua Umum"
   - Hasil: Tampil semua user dengan role "Ketua Umum"
   - Coba search: "Wakil Ketua"
   - Hasil: Tampil semua user dengan role "Wakil Ketua" (bukan "Ketua Umum")
   - Jelaskan: "Search sekarang smart - bisa search by role name"
   
4. (Optional) Filter by role untuk menunjukkan berbagai role:
   - Super Admin
   - Board Member (BPH)
   - Public user
```

**Yang dijelas:**
- "Sistem role-based access control untuk security"
- "Admin mudah manage pengguna dan tracking siapa bisa akses apa"
- "Search yang smart untuk memudahkan filter pengguna"

---

## 🔵 FITUR OPTIONAL (Demo jika waktu memungkinkan - 2-3 menit)

> **Jika ada waktu sisa, pick 1-2 dari berikut**

### A. Sewa Band (jika ada waktu)
```
1. Buka: Booking > Sewa Band (Admin)
2. Tampilkan: 
   - List permintaan sewa band
   - Approve/Reject flow
   - Notifikasi email dan invoice otomatis
```

### B. Struktur Pengurus (jika ada waktu)
```
1. Buka: Struktur > Daftar Pengurus (Admin)
2. Tampilkan:
   - Periode organisasi
   - Struktur BPH dan subsie
   - Edit/Tambah pengurus
3. Buka halaman public: /struktur
   - Tampilkan struktur organsasi yang rapi
```

### C. Pendaftaran Diklat (jika ada waktu)
```
1. Buka halaman public: /diklat
2. Isi form pendaftaran diklat
3. Buka admin: Diklat > Daftar Peserta
   - Tampilkan peserta yang baru daftar
   - Ubah status: Diterima/Ditolak
```

---

## ⚠️ FITUR TIDAK PERLU DIDEMO

❌ **Jangan demo** (kurang penting / kompleks untuk 15 menit):

| Fitur | Alasan |
|-------|--------|
| **Template Surat** | Kurang mengekspos value - hanya management file |
| **Arsip Surat Masuk/Keluar** | Admin feature, kurang menarik untuk audience umum |
| **Persewaan Alat (Keranjang)** | Flow mirip booking studio, redundant untuk demo singkat |
| **Prestaasi & Kegiatan** | Marketing feature, bukan core business flow |
| **Export Report PDF/Excel** | Nice-to-have, bukan essential untuk demo |
| **Edit Transaksi Keuangan Manual** | Jarang dipakai, admin biasanya auto dari booking |

---

## 📝 SCRIPT DEMO (Fulltext untuk presentasi)

### Opening (30 detik)
```
"Halo, nama saya [Nama]. Saya mau demo aplikasi SATPALS yang kami kembangkan 
untuk UKM Satya Palapa.

SATPALS adalah sistem manajemen organisasi yang solve 3 main problems:
1. Booking studio - dulunya manual lewat chat, sekarang online
2. Sewa band - tracking jadi lebih mudah
3. Manajemen keuangan - semua transaksi terpusat dalam satu dashboard

Kami coba demo workflow lengkapnya dalam 15 menit ini.
Mari kita mulai!"
```

### Intro to Public Side (20 detik)
```
"Pertama, kita lihat dari sudut pandang customer. 
Katakanlah ada acara di kampus yang butuh booking studio.
Daripada harus chat ketua atau email, sekarang bisa langsung online disini."
```

### Demo Booking Form (40 detik)
```
"Saya isi form booking:
- Nama dan kontak - otomatis untuk tracking
- Kategori: Saya pilih 'UKM Semua' karena peserta hanya dari UKM
  (Lihat: field 'Jumlah Non-UKM' jadi disable otomatis, 
   ini fix untuk prevent user error yang sebelumnya selalu bikin error)
- Tanggal dan durasi
- Klik submit!"

[Setelah submit]
"Booking berhasil! System auto generate booking code.
Customer dapat konfirmasi langsung dan tunggu approval dari admin."
```

### Transition to Admin (15 detik)
```
"Sekarang mari kita lihat dari sisi admin. 
Setelah customer submit booking, admin dapat notifikasi dan review di dashboard."
```

### Demo Admin Dashboard (30 detik)
```
"Ini dashboard admin. Bisa lihat ringkasan semua aktivitas:
- Total booking bulan ini
- Total sewa band
- 'Menunggu Approval' counter (ini sebelumnya cuma hitung expense, 
  sekarang kami fix jadi hitung income + expense juga)
- Chart aktivitas bulan ini

Admin bisa langsung lihat apa yang perlu di-approve tanpa harus cek satu-satu."
```

### Demo Approval & Automation (1 menit)
```
"Sekarang admin buka booking studio yang baru masuk. 
[Buka detail booking]

Lihat status masih 'Pending'. Admin bisa approve atau reject.
Saya klik approve..."

[Setelah approve]
"Dalam 1 detik:
1. Status berubah jadi 'Approved'
2. Invoice PDF otomatis generate dan bisa didownload
3. Email notifikasi otomatis kirim ke penyewa
4. Pemasukan otomatis tercatat di keuangan

Ini automation yang save waktu admin dari paperwork manual.
Dulu butuh buka invoice template, edit, save, attach, kirim email - sekarang 
semua automatic dalam 1 klik."
```

### Demo Financial Integration (30 detik)
```
"Kita cek keuangan. [Buka dashboard keuangan]

Lihat transaksi yang baru kita approve tadi sudah otomatis masuk:
- Nominal: dari harga booking
- Tipe: Pemasukan (dari booking)
- Status: Approved (auto from booking approval)
- Created by: [admin name] - untuk audit trail

Jadi tidak ada manual entry ke keuangan. Semuanya integrated.
Admin tinggal filter per periode untuk buat laporan keuangan."
```

### Demo User Search (20 detik)
```
"Last feature: user management. [Buka admin users]

Kami improve search functionality - sekarang bisa search by role name.
Dulunya search 'Ketua Umum' ga ketemu karena database store as 'Ketua_Umum'.
Sekarang kami fix dengan smart normalization.
[Coba search: 'Ketua Umum' - show hasil]

Ini small UX improvement tapi improve usability admin."
```

### Closing (30 detik)
```
"Jadi summary aplikasi kami:

✅ Solve booking studio online - no more chat chaos
✅ Approval workflow yang clear dan transparent
✅ Automation yang reduce admin workload (email, invoice, financial tracking)
✅ Centralized dashboard untuk manage semua aspek organisasi
✅ Search dan filtering untuk easy access data

Teknologi: Laravel backend, MySQL database, Tailwind CSS frontend.
Responsive design bisa diakses dari desktop, tablet, atau mobile.

Saya rasa demo singkatnya seperti itu. Terima kasih!"
```

---

## 🕐 TIME BREAKDOWN (15 menit)

| Bagian | Durasi | Keterangan |
|--------|--------|-----------|
| Opening & Context | 2 menit | Jelaskan masalah + solusi |
| Public Booking Form | 2 menit | Demo user flow |
| Admin Dashboard | 1 menit | Ringkasan statistik |
| Approval & Automation | 2 menit | **HIGHLIGHT** - automation benefit |
| Financial Dashboard | 1.5 menit | Tunjukkan integration |
| User Management & Search | 1.5 menit | Tunjukkan fitur admin utils |
| (Optional) Extra Feature | 2-3 menit | Jika audience bertanya / ada waktu sisa |
| **Total** | **~15 menit** | |

---

## ✅ CHECKLIST PRE-DEMO

Sebelum presentasi, pastikan:

- [ ] **Database**: Fresh seed data atau data test yang clean
- [ ] **Akun Test**:
  - [ ] Admin: `admin@test.com` / `password`
  - [ ] Public user test (jika perlu manual booking)
- [ ] **Browser**: Fullscreen, zoom 100%, internet stabil
- [ ] **Data Test**: 
  - [ ] Minimal 1 studio booking pending (untuk approve demo)
  - [ ] Some finance data jika possible (untuk tampilkan integration)
  - [ ] Multiple users dengan role berbeda (untuk demo search)
- [ ] **Email**: Check email config working (jika ingin show inbox terima email)
- [ ] **Server**: `php artisan serve` sudah jalan smooth
- [ ] **Assets**: npm run build sudah di-run (styling correct)
- [ ] **Backup URL**: Screenshot / video backup jika demo gagal live

---

## 🎤 TIPS PRESENTASI

1. **Pace**: Jangan terlalu cepat, beri waktu audience understand flow
2. **Narasi**: Jelaskan "why" bukan cuma "what" - why sistem developed, why fitur penting
3. **Mouse**: Highlight apa yang admin lihat dengan gerak mouse / pointer
4. **Sound**: Kalau ada notification sound dari email, matikan supaya ga aneh
5. **Focus**: Minimize open tabs, jangan buka random windows - distract audience
6. **Contingency**: Jika live demo fail, punya screenshot/video backup
7. **End Note**: Tutup dengan unique value prop - apa yang membuat sistem ini special

---

## 📊 FITUR MATRIX - WAJIB vs OPTIONAL

```
┌────────────────────────────┬────────┬────────────────────────────────┐
│ Fitur                      │ Status │ Alasan                         │
├────────────────────────────┼────────┼────────────────────────────────┤
│ Booking Studio (Public)    │ 🔴 WAJIB │ Core feature, customer pain point |
│ Admin Dashboard            │ 🔴 WAJIB │ Command center, show overview   │
│ Approval Workflow + Email  │ 🔴 WAJIB │ **HIGHLIGHT** automation       │
│ Keuangan Dashboard         │ 🔴 WAJIB │ Financial integration key      │
│ User Search by Role        │ 🔴 WAJIB │ Show UX improvement            │
│ Sewa Band                  │ 🔵 OPTIONAL │ Similar flow, time constraint  │
│ Struktur Pengorus (Public) │ 🔵 OPTIONAL │ Nice-to-have showcase          │
│ Diklat Registration        │ 🔵 OPTIONAL │ Separate module, less critical │
│ Template Surat             │ ⚪ SKIP    │ File management, less impressive │
│ Arsip Surat M/K            │ ⚪ SKIP    │ Admin feature, less relevant    │
│ Persewaan Alat (Keranjang) │ ⚪ SKIP    │ Redundant dengan studio booking │
│ Prestasi & Kegiatan        │ ⚪ SKIP    │ Content management, not core   │
└────────────────────────────┴────────┴────────────────────────────────┘
```

---

**Last Updated**: May 22, 2026  
**Prepared for**: Final Seminar Presentation (Semhas)  
**Duration**: 15 minutes with explanation  
**Version**: 1.0
