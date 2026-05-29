# 🎬 VISUAL DEMO FLOW - 15 MENIT

```
┌─────────────────────────────────────────────────────────────────┐
│                  SATPALS PRESENTATION FLOW                      │
│                     Duration: 15 Minutes                        │
└─────────────────────────────────────────────────────────────────┘

╔═════════════════════════════════════════════════════════════════╗
║                    PHASE 1: OPENING (2 min)                    ║
║─────────────────────────────────────────────────────────────────║
║ Narasi:                                                         ║
║ "Halo, saya demo SATPALS - sistem booking UKM Satya Palapa"   ║
║                                                                 ║
║ Problem Statement:                                              ║
║ ❌ Dulu: Booking manual via chat → kompleks, susah track       ║
║ ❌ Keuangan: Manual di Excel → error-prone                    ║
║                                                                 ║
║ Solution:                                                       ║
║ ✅ Online booking dengan workflow terstruktur                  ║
║ ✅ Automation: email, invoice, financial record auto          ║
║ ✅ Centralized dashboard untuk semua stakeholder              ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║              PHASE 2: PUBLIC BOOKING FORM (2 min)              ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  URL: http://localhost:8000/studio-bookings/create            ║
║                                                                 ║
║  🖥️  USER JOURNEY:                                             ║
║  ┌─────────────────────────────────────┐                      ║
║  │ Customer buka halaman booking       │                      ║
║  │ isi form dengan data:               │                      ║
║  │ - Nama: "Kelompok Musik IT"        │                      ║
║  │ - Email: "music@it.com"            │                      ║
║  │ - Category: "UKM Semua"            │                      ║
║  │   (jumlah_non_ukm field auto-disable) ⭐ BUG FIX!         │                      
║  │ - Tanggal & durasi                 │                      ║
║  │ - Submit                            │                      ║
║  └─────────────────────────────────────┘                      ║
║                                                                 ║
║  📱 RESULT:                                                     ║
║  ✓ Booking berhasil                                            ║
║  ✓ Get booking code: STB-2605-001                              ║
║  ✓ Notification: "Tunggu approval admin"                       ║
║                                                                 ║
║  💬 NARASI:                                                     ║
║  "Dari sisi customer, proses sangat simple. Form validation   ║
║   smart - prevent user error (lihat field disabled otomatis). ║
║   Sekarang admin review di dashboard mereka."                 ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║            PHASE 3: ADMIN LOGIN & DASHBOARD (1 min)            ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  URL: http://localhost:8000/admin/login → /admin/dashboard    ║
║                                                                 ║
║  🔐 LOGIN:                                                      ║
║  Email: admin@test.com                                         ║
║  Password: password                                            ║
║                                                                 ║
║  📊 DASHBOARD VIEW:                                             ║
║  ┌──────────────────────────────────────────────┐             ║
║  │ 📈 Total Booking (bulan ini)      │  15  │  │             ║
║  ├──────────────────────────────────────────────┤             ║
║  │ 🎵 Total Sewa Band               │  8   │  │             ║
║  ├──────────────────────────────────────────────┤             ║
║  │ 🧰 Total Sewa Alat               │  12  │  │             ║
║  ├──────────────────────────────────────────────┤             ║
║  │ ⏳ Menunggu Approval              │  10  │  │  ⭐ FIXED!  ║
║  │    (Income 8 + Expense 2)              │  │             ║
║  └──────────────────────────────────────────────┘             ║
║                                                                 ║
║  💬 NARASI:                                                     ║
║  "Dashboard ini adalah command center. Admin bisa lihat semua ║
║   aktivitas dalam satu view. Lihat 'Menunggu Approval' = 10. ║
║   Ini sudah kami fix - dulu cuma hitung pengeluaran, sekarang ║
║   hitung income + expense, jadi akurat total pending items."  ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║          PHASE 4: APPROVE + AUTOMATION MAGIC (2 min)           ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  URL: http://localhost:8000/admin/bookings/studio             ║
║                                                                 ║
║  WORKFLOW:                                                      ║
║  ┌────────────────────────────────────────────────────────┐   ║
║  │ 1. Buka booking list, filter "Pending"                │   ║
║  │    → Show booking dari "Kelompok Musik IT" yang baru  │   ║
║  │                                                        │   ║
║  │ 2. Klik Detail booking                                │   ║
║  │    → Show: STB-2605-001, customer info, dates, status │   ║
║  │                                                        │   ║
║  │ 3. KLIK APPROVE BUTTON                                │   ║
║  │    🎬 DALAM 1 DETIK, 3 AUTOMATION TERJADI:           │   ║
║  │                                                        │   ║
║  │    ✅ AUTOMATION 1: Invoice PDF Auto-Generate         │   ║
║  │       [Show PDF link]                                 │   ║
║  │       Narasi: "Dulu admin manual buka template, edit, │   ║
║  │                save, attach email. Sekarang otomatis." │   ║
║  │                                                        │   ║
║  │    ✅ AUTOMATION 2: Email Notification Auto-Send      │   ║
║  │       [Show/Check email inbox or log]                │   ║
║  │       Narasi: "Email terkirim ke music@it.com dengan  │   ║
║  │                invoice attachment otomatis"          │   ║
║  │                                                        │   ║
║  │    ✅ AUTOMATION 3: Income Record Auto-Create         │   ║
║  │       [Verify nanti di financial dashboard]           │   ║
║  │       Narasi: "Booking approve = income auto terecord │   ║
║  │                di keuangan, tidak perlu manual entry" │   ║
║  │                                                        │   ║
║  │ 4. Status berubah: PENDING → APPROVED                 │   ║
║  └────────────────────────────────────────────────────────┘   ║
║                                                                 ║
║  🎬 HIGHLIGHT QUOTE:                                            ║
║  \"Automation ini reduce admin workload dari 15-20 menit      ║
║   per booking jadi 30 detik approve click. Multiply dengan    ║
║   100+ bookings per tahun, ini significant time saving!\"     ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║           PHASE 5: FINANCIAL DASHBOARD (1.5 min)              ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  URL: http://localhost:8000/admin/financial                   ║
║                                                                 ║
║  VERIFICATION: Automation actually worked!                     ║
║                                                                 ║
║  📊 DASHBOARD:                                                  ║
║  ┌─────────────────────────────────────────────┐              ║
║  │ 💰 Total Pemasukan:    Rp 2.350.000        │              ║
║  │ 💸 Total Pengeluaran:  Rp 750.000          │              ║
║  │ 📈 Net Saldo:         Rp 1.600.000        │              ║
║  └─────────────────────────────────────────────┘              ║
║                                                                 ║
║  📋 TRANSACTION LIST (Filter: Periode Mei, Type: Pemasukan):  ║
║  ┌──────────────────────────────────────────────┐             ║
║  │ Date    │ Description      │ Amount  │Status│             ║
║  ├──────────────────────────────────────────────┤             ║
║  │ 22/5    │ STB-2605-001    │450.000 │ Appr │ ⭐ Dari      ║
║  │ 20/5    │ STB-2605-002    │200.000 │ Appr │    booking  ║
║  │ 18/5    │ STB-2605-003    │300.000 │ Appr │    yang    ║
║  │ ...     │ ...              │   ...   │  ... │    baru   ║
║  └──────────────────────────────────────────────┘             ║
║                                                                 ║
║  💬 NARASI:                                                     ║
║  "Lihat transaksi yang baru kita approve tadi sudah otomatis  ║
║   masuk ke keuangan. Admin bisa filter per periode untuk buat ║
║   laporan keuangan. Financial data terpusat - single source   ║
║   of truth untuk semua stakeholder."                          ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║         PHASE 6: USER MANAGEMENT & SEARCH (1.5 min)           ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  URL: http://localhost:8000/admin/users                       ║
║                                                                 ║
║  👥 USER LIST:                                                  ║
║  ┌──────────────────────────────────────────┐                ║
║  │ Name        │ Email        │ Role         │                ║
║  ├──────────────────────────────────────────┤                ║
║  │ Ahmad       │ ketua@...    │ Ketua Umum   │                ║
║  │ Budi        │ wakil@...    │ Wakil Ketua  │                ║
║  │ Citra       │ board1@...   │ Board Member │                ║
║  │ Dian        │ board2@...   │ Board Member │                ║
║  └──────────────────────────────────────────┘                ║
║                                                                 ║
║  🔍 DEMO SEARCH (BUG FIX):                                     ║
║                                                                 ║
║  Before (dulu):                                                ║
║  Search \"Ketua Umum\" → Result ga akurat (database store    ║
║                         dengan underscore 'ketua_umum')      ║
║                                                                 ║
║  After (sekarang): ⭐ FIXED!                                   ║
║  ┌──────────────────────────────────────────┐                ║
║  │ Search box: \"Ketua Umum\"                │                ║
║  │ [ENTER]                                   │                ║
║  │ Result:                                   │                ║
║  │  ✓ Ahmad - Ketua Umum       (EXACT MATCH)│                ║
║  │  ✗ Budi - Wakil Ketua       (NOT show)   │                ║
║  └──────────────────────────────────────────┘                ║
║                                                                 ║
║  💬 NARASI:                                                     ║
║  "Kami improve search dengan smart normalization. Sebelumnya  ║
║   search 'Ketua Umum' hasilnya kurang akurat karena database  ║
║   store dengan underscore. Sekarang search akurat, bisa handle║
║   spasi dan underscore. Improvement kecil tapi improve UX."   ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║        PHASE 7: OPTIONAL - EXTRA FEATURE (2-3 min)            ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  IF TIME PERMITS, PICK ONE:                                    ║
║                                                                 ║
║  📱 OPTION A: Sewa Band (2 min)                                ║
║     └─ Similar workflow to studio booking                     ║
║     └─ Show system handles multiple booking types            ║
║                                                                 ║
║  🏢 OPTION B: Struktur Pengurus (1 min)                        ║
║     └─ Show organization structure visualization             ║
║     └─ Nice visual showcase                                   ║
║                                                                 ║
║  ❓ OPTION C: Extended Q&A (3 min)                             ║
║     └─ Answer deeper questions from examiners               ║
║     └─ Demonstrate knowledge beyond core demo                ║
║                                                                 ║
╚═════════════════════════════════════════════════════════════════╝

                                ▼

╔═════════════════════════════════════════════════════════════════╗
║                    PHASE 8: CLOSING (2 min)                   ║
║─────────────────────────────────────────────────────────────────║
║                                                                 ║
║  SUMMARY:                                                       ║
║  ✅ Online booking → reduce manual process                     ║
║  ✅ Automation (email, invoice, finance) → save admin time    ║
║  ✅ Centralized dashboard → easy oversight                    ║
║  ✅ Role-based access → maintain security                     ║
║  ✅ Financial tracking → transparent accounting              ║
║                                                                 ║
║  TECH STACK:                                                    ║
║  • Framework: Laravel 11                                       ║
║  • Database: MySQL 8.0                                         ║
║  • Frontend: Tailwind CSS, responsive design                  ║
║  • Architecture: Modular, tested with UAT                     ║
║                                                                 ║
║  CLOSING STATEMENT:                                             ║
║  \"Aplikasi ini sudah di-test dengan comprehensive UAT       ║
║   scenarios dan siap untuk production deployment. Terima     ║
║   kasih, ada pertanyaan?\"                                    ║
║                                                                 ║
╚═════════════════════════════════════════════════════════════════╝
```

---

## ⏱️ TIME ALLOCATION VISUAL

```
15 MINUTES DEMO
┌─────────────────────────────────────────────────────────────┐
│ Opening       │ Public   │Admin │ Approve  │ Finance │User  │
│ (2 min)       │ Book     │Dash  │ + Auto   │ (1.5)   │Mgmt  │
│               │ (2 min)  │ (1)  │ (2 min)  │         │(1.5) │
├───────────────┼──────────┼──────┼──────────┼─────────┼──────┤
│ ███           │ ██████   │ ███  │ ██████   │ ███████ │ ███  │
│ │             │ │        │ │    │ │ HIGHLIGHT HERE │ │      │
└─────────────────────────────────────────────────────────────┘
0   2          4   6      7   8   9          10.5    12    13.5 15

Optional       │  Q&A / Buffer
(if time ∧ ask)│
───────────────┴─────────────────
```

---

## 🎯 KEY SUCCESS INDICATORS

```
Demo is SUCCESSFUL if:

1. ✅ All 5 core features demonstrated
   □ Public booking form
   □ Admin dashboard
   □ Approval workflow
   □ Financial integration
   □ User management

2. ✅ Automation benefits clearly explained
   □ Email sending works (or shown in logs)
   □ Invoice PDF generated
   □ Financial record auto-created

3. ✅ No live demo crashes
   □ Or gracefully fallback to backup
   □ Continue with screenshots

4. ✅ Stayed within 15 minutes
   □ Core demo ~10 min
   □ Q&A ~2-3 min

5. ✅ Examiners understand value proposition
   □ Problem clearly stated
   □ Solution clearly demonstrated
   □ Benefits clearly communicated
```

---

## 🚨 ABORT CRITERIA

Demo needs adjustment if:

- ❌ Features not accessible (check database/server)
- ❌ Constant errors (rollback to backup)
- ❌ Time overrun by 5+ minutes (skip optional features)
- ❌ Cannot demonstrate key automation (switch to backup)

---

**Print this page as reference during presentation!**
