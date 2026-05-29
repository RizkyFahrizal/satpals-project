# ✅ DETAILED TEST CASE DEMO - SATPALS PRESENTASI

## Test Case Setup
**Target**: 15-menit demo presentasi  
**Audience**: Dosen pembimbing + penguji semhas  
**Test Environment**: Local development (php artisan serve)

---

## TEST CASE 1: PUBLIC SIDE - BOOKING STUDIO

### 🎯 Objective
Demo customer journey dari browse hingga submit booking studio

### 📋 Test Data Preparation
```
Pre-requisite:
- Database sudah ter-seed dengan studio list
- Server running di: http://localhost:8000
- Browser: Chrome/Firefox (fullscreen)
```

### 🔄 Test Steps

#### Step 1.1: Buka Halaman Booking Studio
```
Action: Buka URL: http://localhost:8000/studio-bookings/create

Expected Result:
✓ Form booking studio tampil
✓ Semua field terlihat: nama, email, no HP, kategori, dll
✓ Responsive layout (desktop view)
```

#### Step 1.2: Isi Form Booking
```
Data Input:
┌─────────────────────┬──────────────────────┐
│ Field               │ Value                │
├─────────────────────┼──────────────────────┤
│ Nama Penyewa        │ "Kelompok Musik IT"  │
│ Email              │ "music@it.com"       │
│ No HP              │ "087654321098"       │
│ Kategori           │ "UKM Semua"          │
│ Jumlah UKM         │ 8                    │
│ Jumlah Non-UKM     │ - (disabled/auto)    │
│ Tanggal Mulai      │ 3 hari dari sekarang │
│ Durasi Sewa        │ 2 jam                │
│ Catatan            │ "Untuk latihan"      │
└─────────────────────┴──────────────────────┘

Action:
1. Klik field "Nama Penyewa", isi "Kelompok Musik IT"
2. Klik field "Email", isi "music@it.com"
3. Klik field "No HP", isi "087654321098"
4. Klik radio button "UKM Semua" untuk kategori
5. Observe: Field "Jumlah Non-UKM" harus ter-disable/hidden
6. Klik field "Jumlah UKM", isi "8"
7. Klik date picker "Tanggal Mulai", select 3 hari ke depan
8. Klik field "Durasi Sewa", isi "2" (jam)
9. Klik field "Catatan", isi "Untuk latihan"

Expected Result:
✓ Semua field dapat diisi
✓ "Jumlah Non-UKM" disabled ketika "UKM Semua" dipilih (BUG FIX: exclude_unless rule)
✓ Tidak ada error validation saat isi form
✓ Form valid untuk di-submit
```

#### Step 1.3: Submit Booking
```
Action:
1. Klik button "PESAN STUDIO" / "SUBMIT BOOKING"

Expected Result:
✓ Form di-submit tanpa error
✓ Redirect ke halaman success/confirmation
✓ Display booking code (contoh: "STB-2605-001234")
✓ Pesan: "Booking Anda sudah terkirim, silakan tunggu approval admin"
✓ Option download invoice atau back to home
```

**Demo Narasi:**
> "Jadi dari sisi customer, proses booking sangat simple dan straightforward. 
> Mereka isi form, submit, dan langsung dapat booking code. 
> Sistem otomatis handle validation - contohnya ketika mereka pilih 'UKM Semua', 
> field 'Jumlah Non-UKM' jadi disabled untuk prevent error.
> Sekarang admin akan review booking ini di dashboard mereka."

---

## TEST CASE 2: ADMIN SIDE - LOGIN & DASHBOARD

### 🎯 Objective
Demo admin dashboard yang menampilkan overview semua aktivitas

### 📋 Test Data Preparation
```
Admin Account:
- Email: admin@test.com
- Password: password
- Role: Super Admin

Ensure:
- Sudah ada minimal 1-2 booking studio pending
- Sudah ada some financial data (both income & expense)
```

### 🔄 Test Steps

#### Step 2.1: Admin Login
```
Action:
1. Buka URL: http://localhost:8000/admin/login
2. Isi Email: "admin@test.com"
3. Isi Password: "password"
4. Klik "Login"

Expected Result:
✓ Login berhasil
✓ Redirect ke dashboard admin
✓ Session active (sidebar terlihat, logout button visible)
```

#### Step 2.2: Explore Dashboard
```
Action:
1. Di halaman dashboard (/admin/dashboard), lihat semua statistik

Expected Result:
✓ Display Card 1: Total Booking Bulan Ini = [X records]
✓ Display Card 2: Total Sewa Band = [X records]
✓ Display Card 3: Total Sewa Alat = [X records]
✓ Display Card 4: "Menunggu Approval" = [X] (THIS IS FIXED - hitung Income + Expense)
✓ Chart/Graph: Aktivitas bulan ini atau activity timeline
✓ Tombol quick action ke berbagai modul (Bookings, Sewa, Keuangan, etc)
```

**Counter "Menunggu Approval" Detail:**
```
✓ Counter harus hitung:
  - Pending Expense records
  - Pending Income records
  - Total keduanya = "Menunggu Approval" value
  
✓ (BUG FIX VERIFICATION) Jika ada 8 pending income + 2 pending expense,
  counter harus show "10" bukan "2"
```

**Demo Narasi:**
> "Ini adalah command center admin. Dengan satu pandangan, mereka bisa lihat:
> - Berapa banyak booking yang pending review
> - Berapa sewa band masuk minggu ini
> - Dan yang penting: 'Menunggu Approval' counter sekarang include BOTH 
>   income dan expense (sebelumnya cuma hitung expense aja, jadi selalu kurang).
> 
> Kalau misalnya ada 8 pemasukan booking yang pending dan 2 pengeluaran yang pending,
> admin akan lihat 'Menunggu Approval' = 10, bukan 2 seperti dulu."

---

## TEST CASE 3: ADMIN SIDE - APPROVE BOOKING STUDIO

### 🎯 Objective
Demo approval workflow + automation (email, invoice, financial integration)

### 📋 Test Data Preparation
```
Booking Studio Pending:
- Cari booking yang status = "Pending"
- Gunakan booking yang baru dibuat di Test Case 1, atau use existing test data
```

### 🔄 Test Steps

#### Step 3.1: Buka Booking Studio List
```
Action:
1. Di admin dashboard, klik menu: "Bookings" > "Studio Bookings"
   atau buka URL: http://localhost:8000/admin/bookings/studio
   
2. Lihat list bookings dengan status filter

Expected Result:
✓ List booking studio tampil
✓ Filter available: Status (Pending, Approved, Rejected, Completed)
✓ Tabel columns: Booking Code, Renter, Date, Duration, Status, Action
✓ Sorting & pagination working
```

#### Step 3.2: Filter & Find Pending Booking
```
Action:
1. Klik filter "Status" dropdown
2. Pilih "Pending"
3. List akan show hanya booking dengan status Pending

Expected Result:
✓ List updated to show only Pending bookings
✓ Booking yang kita create tadi ada di list
```

#### Step 3.3: Open Booking Detail
```
Action:
1. Klik on salah satu pending booking (atau klik "View" / "Detail" button)
2. Halaman detail booking terbuka

Expected Result:
✓ Display full booking info:
  - Booking Code
  - Renter Name & Email & Phone
  - Category & Participant Count
  - Booking Date & Duration
  - Status: "Pending"
  - Created At timestamp
  
✓ Action buttons visible:
  - Approve button
  - Reject button
  - Delete button
```

#### Step 3.4: Approve Booking
```
Action:
1. Klik button "Approve" atau "Terima"
2. (Optional) Jika ada modal, bisa isi catatan approval
3. Klik "Konfirmasi Approval"

Expected Result:
✓ Booking status berubah: "Pending" → "Approved"
✓ Timestamp "Approved At" ter-update
✓ Display success message: "Booking berhasil di-approve"
```

#### Step 3.5: Verify Automations Triggered
```
Action - Check 1 (Invoice PDF Generated):
1. Di booking detail atau di nearby section, lihat "Invoice" link/button
2. Klik link invoice
3. PDF browser akan membuka atau download triggered

Expected Result:
✓ Invoice PDF dapat di-download
✓ Invoice berisi: Booking code, renter info, dates, amount, terms, etc
✓ Format profesional dengan UKM logo/header jika ada
```

```
Action - Check 2 (Email Sent to Renter):
1. Check email inbox (jika email config working):
   - Buka email provider (Gmail, etc) yang menerima email
   - Cari email dari sender "satpals@" atau "noreply@"
   - Subject harus contain booking code atau "Booking Approved"

Expected Result:
✓ Email terkirim ke renter email address
✓ Email content berisi: booking confirmation, booking code, dates, amount, invoice attachment
✓ Professional HTML template
✓ CTA: "Download Invoice" atau view in app

(ALTERNATIVE jika email tidak setup: Check email logs)
- Buka terminal, cek log email: grep "BookingApprovedMail" storage/logs/laravel.log
- Harus ada log entry: "Mail sent to music@it.com"
```

```
Action - Check 3 (Financial Integration):
1. Kembali ke admin panel
2. Buka menu: "Keuangan" > "Dashboard"
   atau URL: http://localhost:8000/admin/financial

Expected Result:
✓ Dashboard keuangan tampil
✓ Pemasukan (Income) bertambah sesuai booking price
✓ Di tabel transaksi, ada entry baru:
  - Type: "Pemasukan"
  - Description: Dari booking studio code
  - Amount: Sesuai harga booking
  - Status: "Approved"
  - Source: Automatically linked ke booking
```

**Demo Narasi (Whole Section 3):**
> "Sekarang kita lihat dari admin side. Admin buka booking list yang pending.
> Ada 1 booking dari kelompok musik IT. Admin buka detail dan klik Approve.
> 
> Yang magical di sini adalah AUTOMATION yang terjadi:
> 
> [Point to Invoice] Satu: Invoice PDF otomatis generated. Dulu admin harus:
> - Buka template invoice di Word/Excel
> - Manual edit tanggal, nama, amount
> - Save as PDF
> - Sekarang: otomatis dalam 1 detik.
> 
> [Point to Email] Dua: Email notifikasi otomatis terkirim ke penyewa.
> Penyewa dapat invoice dan approval confirmation langsung.
> Dulu admin harus compose email, attach file, send one-by-one.
> 
> [Point to Financial Dashboard] Tiga: Pemasukan ini otomatis masuk ke keuangan.
> Admin tidak perlu buka keuangan modul, manual input transaksi.
> Semuanya integrated - approval booking = automatic financial record.
> 
> Jadi automation ini reduce admin workload dari maybe 15-20 menit per booking
> jadi cuma 30 detik approve click. Multiply dengan 100+ bookings per tahun,
> ini significant time saving."

---

## TEST CASE 4: ADMIN SIDE - FINANCIAL DASHBOARD

### 🎯 Objective
Demo financial tracking dan data integration dari bookings

### 📋 Test Data Preparation
```
Requirements:
- Minimal 3-5 approved bookings (dari different booking types: studio, band, equipment)
- Minimal 2-3 expense records (manual entry)
- Data dari current month or selectable period
```

### 🔄 Test Steps

#### Step 4.1: Open Financial Dashboard
```
Action:
1. Di admin sidebar, klik: "Keuangan" > "Dashboard"
   atau direct URL: http://localhost:8000/admin/financial

Expected Result:
✓ Financial dashboard tampil
✓ KPI Cards visible:
  - Total Pemasukan (bulan ini / periode selected)
  - Total Pengeluaran
  - Net Income / Saldo
  - Jumlah Transaksi
✓ Chart: Trend pemasukan/pengeluaran bulanan
✓ Recent transaction list
```

#### Step 4.2: Apply Filter
```
Action:
1. Di filter section, tentukan:
   - Periode: Select current month or custom date
   - Type: Pilih "Pemasukan" (Income)

Expected Result:
✓ Dashboard re-calculate dengan filter applied
✓ KPI cards update sesuai filter
✓ Transaction list show hanya Pemasukan
✓ Chart updated
```

#### Step 4.3: Find Booking Transaction
```
Action:
1. Di transaction list, cari entry yang berasal dari studio booking tadi
2. Bisa gunakan Search by deskripsi atau filter

Expected Result:
✓ Transaction record visible dengan detail:
  - Date
  - Description (booking code reference)
  - Amount (studio booking price)
  - Type: "Pemasukan"
  - Status: "Approved"
  - Created By: Admin name (audit trail)
  - Link/Reference ke booking source (if available)
```

#### Step 4.4: Verify Multiple Data Sources
```
Action:
1. Tambahin filter: show semua Pemasukan (tidak filter by type)
2. Observe list

Expected Result:
✓ Mixed transactions visible:
  - Studio Booking revenue
  - Band Rental revenue
  - Equipment Rental revenue
  - Lainnya jika ada
✓ Each dengan source tracking (which booking/transaction)
```

#### Step 4.5: (Optional) Export Report
```
Action (if time permits):
1. Look for "Export" button
2. Klik export ke PDF atau Excel (if feature available)

Expected Result:
✓ Report file downloaded
✓ Report format: clean, professional dengan logo
✓ Include: date range, summary, detailed transaction list
```

**Demo Narasi:**
> "Ini adalah financial dashboard. Admin bisa lihat overview:
> - Total masuk (dari semua sumber: booking, sewa band, etc)
> - Total keluar (pengeluaran operasional)
> - Net saldo
> 
> Dari sini admin bisa generate laporan untuk:
> - Treasurer report ke pengurus
> - Financial audit
> - Budget planning
> 
> Sebelumnya semua ini manual di Excel, mudah error, susah track.
> Sekarang terpusat, real-time, dan integrated.
> 
> Lihat di transaksi detail, setiap income dari booking studio yang kita approve tadi
> sudah otomatis masuk sini dengan source tracking."

---

## TEST CASE 5: ADMIN SIDE - USER MANAGEMENT & ROLE SEARCH

### 🎯 Objective
Demo user management dan improved search functionality

### 📋 Test Data Preparation
```
Requirements:
- Database sudah ter-seed dengan multiple users
- Users dengan role berbeda: Super Admin, Ketua Umum, Wakil Ketua, dll
- Min 5-10 user records untuk demo search
```

### 🔄 Test Steps

#### Step 5.1: Open User Management
```
Action:
1. Di admin sidebar, klik: "Admin" > "Manajemen Pengguna"
   atau direct URL: http://localhost:8000/admin/users

Expected Result:
✓ User management page tampil
✓ List of users dengan columns:
  - No / ID
  - Nama
  - Email
  - Role
  - Status
  - Action (Edit, Delete)
✓ Pagination for large lists
```

#### Step 5.2: Observe Roles
```
Action:
1. Lihat kolom "Role" di user list
2. Observe variety of roles

Expected Result:
✓ Various roles visible:
  - super_admin (display: "Super Admin")
  - ketua_umum (display: "Ketua Umum")
  - wakil_ketua_umum (display: "Wakil Ketua Umum") - NOTE: dengan underscore di DB
  - board_member
  - etc
```

#### Step 5.3: Test Search by Role Name
```
Action:
1. Klik search field (atau cari field search di page)
2. Type: "Ketua Umum" (spaced, display format)
3. Press Enter atau klik Search

Expected Result:
✓ Search results show ONLY users with "Ketua Umum" role
✓ Other roles NOT included (important: "Wakil Ketua Umum" should NOT show)
✓ Results accurate (search smart dengan normalization: 
   handle underscore/space mismatch di database)
```

#### Step 5.4: Test Search with Partial Match
```
Action:
1. Clear search
2. Type: "Wakil" (partial role name)
3. Press Enter

Expected Result:
✓ Search results show users with role containing "Wakil"
✓ Example: "Wakil Ketua Umum", "Wakil Bendahara", etc
```

#### Step 5.5: Test Search by Name
```
Action:
1. Clear search
2. Type: User name (e.g., "Ahmad" atau "Budi")
3. Press Enter

Expected Result:
✓ Search results show users matching name
✓ Works by nama
```

**Demo Narasi:**
> "Feature terakhir: User Management. Admin bisa manage semua pengguna,
> assign role, dan track permissions.
> 
> Yang kami improve di sini adalah search functionality.
> Sebelumnya, kalau admin search 'Ketua Umum', hasilnya kurang akurat
> karena database store dengan underscore: 'ketua_umum'.
> 
> Sekarang kami implement smart search dengan normalization.
> [Demo search 'Ketua Umum'] - lihat hasilnya akurat, cuma show Ketua Umum users.
> [Demo search 'Wakil'] - show semua dengan 'Wakil' di role name.
> 
> Small improvement tapi improve usability."

---

## TEST CASE 6: (OPTIONAL) SEWA BAND WORKFLOW

### 🎯 Objective
Demo booking sewa band dengan similar workflow ke studio booking

### 📋 Test Data (if time permits)
```
- Public form: http://localhost:8000/bands/request
- Admin: http://localhost:8000/admin/bookings/band
- Similar flow: Public submit → Admin approve → Email + Invoice auto
```

### 🔄 Test Steps (Abbreviated)
```
1. Buka public halaman sewa band
2. Isi form request: band type, date, budget, contact
3. Submit
4. Buka admin panel
5. Approve request
6. Verify: Email sent, Invoice generated, Financial record updated
```

**Demo Narasi (if included):**
> "Workflow sewa band sama seperti studio booking:
> - Customer request band via online form
> - Admin review dan approve
> - Email notifikasi + invoice otomatis
> - Revenue auto-recorded di keuangan
> 
> Setiap booking type (studio, band, equipment) integrated dalam satu ecosystem."

---

## TEST CASE 7: (OPTIONAL) STRUKTUR PENGURUS

### 🎯 Objective
Demo organizational structure display (nice-to-have showcase)

### 🔄 Test Steps (Abbreviated)
```
1. Buka public halaman: http://localhost:8000/struktur
2. Lihat struktur pengurus ditampilkan rapi (BPH, subsie, member info)
3. (Admin) Buka admin panel untuk manage struktur
```

---

## 📋 CRITICAL CHECKLIST SEBELUM PRESENTASI

### Database & Server
- [ ] Fresh database seed dengan test data
- [ ] `php artisan migrate` sudah run
- [ ] `php artisan db:seed` sudah run (atau manual insert test data)
- [ ] `php artisan serve` berjalan stable (no errors)
- [ ] Database connection tested (sanity check: query count > 0)

### Test Accounts
- [ ] Admin account exists: `admin@test.com` / `password` (accessible)
- [ ] Test email ready: `music@it.com` (untuk receive booking notification)

### Test Data
- [ ] Studio list seeded (minimal 1-2 studio records)
- [ ] Minimal 1-2 pending bookings exist (untuk approve demo)
- [ ] Financial data exist (beberapa transaksi untuk dashboard demo)
- [ ] Users with various roles seeded (untuk search demo)

### UI/UX
- [ ] Browser fullscreen, zoom 100%
- [ ] Dark mode / Light mode: consistent dengan design
- [ ] Responsive: check pada desktop resolution 1920x1080 atau similar
- [ ] All assets loaded: CSS, JS, images (no 404 errors)

### Features Tested
- [ ] Booking form validation working (jumlah_non_ukm exclude_unless tested)
- [ ] Admin dashboard counter accurate (income + expense total)
- [ ] Approval workflow runs without error
- [ ] Email sending works (or check logs if email not configured)
- [ ] Invoice PDF generates properly
- [ ] Financial auto-integration works (booking → income record)
- [ ] User search with role filter accurate
- [ ] No console errors (F12 check)

### Backup Plan
- [ ] Screenshot of each step (fallback if live demo fails)
- [ ] Video recording of flow (just in case)
- [ ] SQL backup of database (untuk reset if data gets messy)

### Environment Setup
- [ ] `.env` file correctly configured
- [ ] Database `.env` credentials correct
- [ ] `MAIL_*` config set (even if fake driver for demo)
- [ ] `APP_DEBUG=true` (untuk helpful error messages jika ada issue)

---

## 🎬 DEMO FLOW SUMMARY

```
Total Time: 15 Menit

├─ 0:00 - 2:00  Opening & Context
│  └─ Explain problem + solution
│
├─ 2:00 - 4:00  PUBLIC SIDE: Booking Form Demo
│  └─ Fill form, submit booking
│
├─ 4:00 - 5:00  ADMIN LOGIN & DASHBOARD
│  └─ Show overview, pending counter (fixed)
│
├─ 5:00 - 7:00  ADMIN APPROVAL & AUTOMATION
│  └─ Approve booking, show email+invoice+finance auto
│
├─ 7:00 - 8:30  FINANCIAL DASHBOARD
│  └─ Show integrated transactions, filter, tracking
│
├─ 8:30 - 10:00 USER MANAGEMENT & SEARCH
│  └─ Demo role-based search improvement
│
├─ 10:00 - 13:00 OPTIONAL FEATURES (if time remains)
│  ├─ Sewa Band workflow
│  ├─ Struktur Pengurus
│  └─ atau Q&A
│
└─ 13:00 - 15:00 Closing + Q&A
   └─ Summary, key value props, thank you
```

---

**Last Updated**: May 22, 2026  
**Version**: 1.0  
**For**: Seminar Presentasi (Semhas)  
**Status**: Ready for Review
