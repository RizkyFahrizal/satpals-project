# 🎯 QUICK REFERENCE - DEMO PRESENTASI 15 MENIT

**Print this page dan bawa ke presentasi sebagai cheat sheet!**

---

## 🚀 PRE-DEMO CHECKLIST (5 menit sebelum mulai)

```
□ Server running: php artisan serve (port 8000)
□ Browser fullscreen, zoom 100%
□ Test admin login: admin@test.com / password ✓
□ Test booking data exist (at least 1 pending)
□ Email or logs ready to check notification
□ Volume speaker: MUTE (no notification sounds)
□ Network stable & responsive
□ All tabs closed except 1 app window
```

---

## ⏱️ TIMELINE - 15 MENIT

| Waktu | Kegiatan | Durasi |
|-------|----------|--------|
| 0:00 | Opening + Problem statement | 2 menit |
| 2:00 | 🌐 PUBLIC: Booking form | 2 menit |
| 4:00 | 🔐 ADMIN: Dashboard | 1 menit |
| 5:00 | ✅ ADMIN: Approve + Automation | 2 menit |
| 7:00 | 💰 ADMIN: Financial dashboard | 1.5 menit |
| 8:30 | 👥 ADMIN: User search | 1.5 menit |
| 10:00 | (OPTIONAL) Extra feature OR Q&A | 3 menit |
| 13:00 | Closing | 2 menit |

---

## 📝 DEMO SEQUENCE

### PHASE 1: OPENING (2 menit)
```
Script:
"Halo, saya [Nama]. Ini demo SATPALS - sistem manajemen UKM Satya Palapa.

Masalah dulu:
- Booking studio manual via chat → susah tracking
- Sewa band kompleks, approval slow
- Keuangan berantakan di Excel

Solusi kami:
- Online booking dengan approval workflow
- Automation: email, invoice, financial record
- Dashboard terpusat untuk semua data

Mari kita lihat sistemnya bekerja."
```

---

### PHASE 2: PUBLIC BOOKING (2 menit)

**URL**: http://localhost:8000/studio-bookings/create

**Flow**:
```
1. Open booking form
   - Narasi: "Customer bisa booking studio langsung online"
   
2. Isi form:
   - Nama: "Kelompok Musik IT"
   - Email: "music@it.com"
   - HP: "087654321098"
   - Kategori: "UKM Semua" ← HIGHLIGHT: jumlah_non_ukm field jadi disable
   - Jumlah UKM: 8
   - Tanggal: 3 hari depan
   - Durasi: 2 jam
   - Catatan: "Untuk latihan"
   
3. Submit
   - Narasi: "Booking berhasil! Dapat booking code STB-XXXXX. 
             Sekarang admin review di dashboard."
```

---

### PHASE 3: ADMIN DASHBOARD (1 menit)

**URL**: http://localhost:8000/admin/login → /admin/dashboard

**Flow**:
```
1. Login: admin@test.com / password

2. Show dashboard:
   - Narasi: "Ini dashboard admin. Admin bisa lihat overview semua aktivitas."
   
3. Point ke stat cards:
   - Total Booking
   - Sewa Band
   - Sewa Alat
   - "Menunggu Approval" ← SHOW FIXED: Income + Expense now included
   
4. Narasi: "Dulu counter 'Menunggu Approval' cuma hitung pengeluaran. 
            Sekarang kami fix jadi hitung income + expense, 
            jadi akurat reflect total pending items."
```

---

### PHASE 4: APPROVE BOOKING (2 menit)

**URL**: /admin/bookings/studio

**Flow**:
```
1. Buka studio bookings list
   - Filter status: "Pending"
   - Narasi: "Admin lihat booking yang pending, termasuk yang baru dari customer"
   
2. Klik booking detail / open
   - Show: Status "Pending", customer info, booking details
   
3. KLIK APPROVE
   - Narasi: "Admin klik Approve. Dalam 1 detik, 3 automation terjadi:"
   
4. SHOW AUTOMATION 1: Invoice PDF
   - "Lihat, invoice otomatis generate. 
    Dulu admin manual buka template, edit, save PDF, attach email. 
    Sekarang: otomatis."
   - Download / show invoice link
   
5. SHOW AUTOMATION 2: Email Notification
   - Narasi: "Email notifikasi kirim otomatis ke penyewa. 
             Mereka dapat approval + invoice."
   - (Check email inbox / show log)
   
6. SHOW AUTOMATION 3: Financial Record
   - Narasi: "Booking approve = income auto tercatat di keuangan.
             Tidak perlu manual entry."
   - Will verify di PHASE 5
```

---

### PHASE 5: FINANCIAL DASHBOARD (1.5 menit)

**URL**: /admin/financial

**Flow**:
```
1. Open financial dashboard
   - Narasi: "Financial dashboard menampilkan overview pemasukan/pengeluaran"
   
2. Show KPI cards:
   - Total Pemasukan
   - Total Pengeluaran
   - Net Saldo
   
3. Filter: Periode (bulan ini) + Type (Pemasukan)

4. Find booking transaction:
   - Narasi: "Lihat di transaksi list, ada entry dari booking studio 
             yang baru kita approve. Amount, date, status semuanya auto-filled."
   - Show: Transaction detail dengan:
     * Amount = booking price
     * Type = "Pemasukan"
     * Status = "Approved"
     * Reference to booking
     * Created by = admin name
     
5. Narasi: "Financial data terpusat. Admin bisa filter, search, 
            generate report untuk treasurer atau audit."
```

---

### PHASE 6: USER MANAGEMENT & SEARCH (1.5 menit)

**URL**: /admin/users

**Flow**:
```
1. Open user management
   - Narasi: "Admin panel untuk manage pengguna dan permission"
   
2. Show user list dengan roles:
   - Narasi: "Lihat users dengan role berbeda: Super Admin, Ketua Umum, dll"
   
3. DEMO SEARCH (BUG FIX):
   - Narasi: "Kami improve search. Dulu kalau search 'Ketua Umum' 
             hasilnya kurang akurat karena database store dengan underscore."
   
   - Search "Ketua Umum":
     * Result: only show users with Ketua Umum role
     * NOT show Wakil Ketua
     * Narasi: "Sekarang search smart, bisa normalize spasi/underscore"
   
   - Search "Wakil":
     * Result: show all with "Wakil" in role (Wakil Ketua, Wakil Bendahara, etc)
```

---

### PHASE 7: OPTIONAL (3 menit - if time permits)

**Pick ONE:**

#### Option A: Sewa Band Demo
```
URL: /bands/request (public) → /admin/bookings/band (admin)

Flow:
1. Public: Fill band rental request form
2. Admin: Show pending, then approve
3. Same automation: email, invoice, financial record
```

#### Option B: Struktur Pengurus Demo
```
URL: /struktur (public) → /admin/struktur (admin)

Flow:
1. Public: Show organization structure visualization
2. Admin: Show how to manage struktur, add member to periode
```

#### Option C: Extended Q&A
```
Dengarkan pertanyaan dari audiens, jawab dengan detail
Tunjukkan fitur lain yg relevan dengan pertanyaan
```

---

### PHASE 8: CLOSING (2 menit)

**Script**:
```
"Jadi ringkasan SATPALS:

✅ Online booking → reduce manual process
✅ Automation (email, invoice, financial) → save admin time
✅ Centralized dashboard → easy overview
✅ Role-based access → maintain security
✅ Financial tracking → transparent accounting

Tech stack: Laravel, MySQL, Tailwind, responsive design.

Aplikasi ini sudah di-test dengan UAT scenarios dan siap untuk production.

Terima kasih, silakan bertanya ada yang kurang jelas."
```

---

## 🛠️ TROUBLESHOOTING

### ❌ Booking form tidak bisa di-submit
- Check: Validasi error message di form
- Solution: Make sure date >= hari ini, durasi valid (1-30)
- Reset: Fresh browser tab, clear localStorage

### ❌ Admin login gagal
- Check: Email/password typo
- Solution: Use correct credential atau create test user
- SQL: `SELECT * FROM users WHERE email='admin@test.com'`

### ❌ Invoice PDF tidak generate
- Check: Storage permission, temp directory accessible
- Check: Mailable class exists: `app/Mail/BookingApprovedMail.php`
- Log: `tail storage/logs/laravel.log` untuk see error

### ❌ Email tidak terkirim
- Check: `.env` MAIL_DRIVER (gunakan 'log' untuk dev)
- Check: Log file: `storage/logs/laravel.log` untuk see email attempt
- Fallback: Show log evidence email tried to send

### ❌ Dashboard counter tidak akurat
- Check: Database ada pending income & expense records
- Check: Counter query di AdminController (line ~50) include both
- Refresh: Reload page (cache?), atau clear cache: `php artisan cache:clear`

### ❌ Search role tidak work
- Check: Users database populated dengan role values
- Check: Search normalized role (UserController::index)
- Test: Direct query: `SELECT * FROM users WHERE role LIKE '%ketua%'`

### ❌ General error / bug appears
- **CALM DOWN** - switch to backup screenshot/video
- Continue narasi: "Ini edge case yang kami dokumentasikan, 
  di production sudah di-fix dengan [solution]"
- Or: "Server error, coba refresh halaman..."
- Fallback: Show source code di GitHub repo untuk explain architecture

---

## 📱 EMERGENCY BACKUP PLAN

**Jika live demo crash:**

1. **Immediate**: Switch ke fullscreen screenshot/diagram
2. **Narasi**: Continue explaining flow menggunakan screenshot
3. **Backup**: Have video recording of previous successful demo
4. **Code**: Show relevant source code di VS Code / GitHub

---

## 🎤 KEY TALKING POINTS

Jika ada waktu atau pertanyaan, highlight poin-poin ini:

```
1. AUTOMATION = Time Saving
   "Dari ~15 menit approve manual jadi 30 detik automated"
   
2. INTEGRATION = Single Source of Truth
   "Booking, Band, Equipment, Finance semuanya integrated"
   
3. TRANSPARENCY = Clear Workflow
   "Customer bisa track status real-time, admin bisa audit semua"
   
4. SCALABILITY = Ready for Growth
   "Built dengan Laravel - mudah add fitur baru"
   
5. SECURITY = Role-Based Access
   "Different users different permissions - prevent unauthorized access"
```

---

## 📸 SCREENSHOT BACKUP POINTS

Jika live demo gagal, have screenshots of:
1. Booking form filled
2. Success confirmation page
3. Admin dashboard (stat cards)
4. Pending booking in admin
5. Invoice PDF
6. Financial transaction
7. User search results

---

## 💬 EXPECTED QUESTIONS & ANSWERS

| Q | A |
|---|---|
| Berapa lama development? | ~[X bulan], dengan [team size]. Iterative development dengan UAT. |
| Database apa? | MySQL 8.0. ERD sudah kami design dengan [tool]. |
| Apa backend framework? | Laravel 11, PHP 8.2. Chosen untuk productivity + community support. |
| Bagaimana security? | CSRF protection, SQL injection prevention, role-based auth, email verification. |
| Production ready? | Ya, sudah UAT, code reviewed, dan ready to deploy. |
| Maintenance plan? | Documented processes, backup strategy, update schedule planned. |

---

## ✅ AFTER DEMO CHECKLIST

```
□ Thank the audience
□ Ask if ada pertanyaan
□ Exchange contact info dengan penanya
□ Close presentation software properly
□ Backup database state (snapshot)
□ Thank the examiners
□ Collect feedback (jika ada form)
```

---

**GOOD LUCK! 🚀**

*Jangan lupa: Demo adalah tentang show value, bukan showcase every feature.
Keep it simple, keep it focused, keep it impressive.*

Last Updated: May 22, 2026
