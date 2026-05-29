# 🔧 SETUP & PERSIAPAN

Folder ini berisi dokumentasi untuk **setup database dan persiapan** sebelum demo presentasi.

---

## 📂 FILE YANG ADA

| File | Tujuan |
|------|--------|
| **DATABASE_TEST_DATA_SETUP.md** | Setup database dengan test data lengkap |
| **DEMO_FILES_CHECKLIST.txt** | Checklist semua file dokumentasi |

---

## 🎯 TUJUAN

Memastikan database dan semua data yang diperlukan **sudah siap** sebelum melaksanakan demo.

---

## 📋 SETUP OPTIONS

### Option 1: Fresh Database (Recommended)
```bash
php artisan migrate:fresh --seed
```

### Option 2: Manual SQL Script
Lihat: `DATABASE_TEST_DATA_SETUP.md` → Copy-paste SQL script

### Option 3: Use Existing Database
Pastikan sudah ada:
- Admin account: admin@test.com / password
- Min 1 pending booking
- Min 2-3 approved bookings
- Min 5-10 users dengan berbagai roles

---

## ✅ PRE-DEMO DATABASE CHECKLIST

```
□ Admin account: admin@test.com / password ✓
□ At least 1 studio record
□ At least 1 pending booking (untuk approve demo)
□ At least 2-3 approved bookings (untuk keuangan demo)
□ At least 5-10 users dengan berbagai roles (untuk search demo)
□ Email notification recipient ready (atau logs enabled)
```

---

## 📝 COMMON SETUP ISSUES

| Issue | Solution |
|-------|----------|
| Database not created | Run: php artisan migrate |
| Admin login fails | Check: .env credentials, create user |
| No booking data | Run seeder atau insert test data manually |
| Email config | Update .env MAIL_* settings |

---

## 🚀 RECOMMENDED TIMELINE

1. **H-3 hari**: Siapkan database schema (migrate)
2. **H-2 hari**: Setup test data (seed)
3. **H-1 hari**: Verify database dan test login
4. **H-30 min**: Reset database ke clean state
5. **H-5 min**: Final verification

---

## 📚 RELATED DOCUMENTATION

- See: `DATABASE_TEST_DATA_SETUP.md` untuk detail lengkap
- See: ../testing-qa/DETAILED_TEST_CASE_SEMHAS.md untuk test verification

---

**Start setup: DATABASE_TEST_DATA_SETUP.md →**
