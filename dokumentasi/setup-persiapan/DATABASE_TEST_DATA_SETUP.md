# 🗄️ DATABASE TEST DATA SETUP - UNTUK DEMO PRESENTASI

---

## 📋 PRE-DEMO DATABASE CHECKLIST

### ✅ What You Need Ready

```
Admin Account:
✓ Email: admin@test.com
✓ Password: password
✓ Role: super_admin

Studio Data:
✓ Minimal 1-2 studio records (for public booking form)
✓ Minimal 1 pending booking (untuk approve demo)

Financial Data:
✓ At least 2-3 approved bookings (untuk show in finance)
✓ Some expense records optional (untuk show balance)

User Data:
✓ 5-10 users dengan berbagai role
✓ Include: Ketua Umum, Wakil Ketua Umum, Board Members (untuk search demo)

Email:
✓ Test email ready: music@it.com (untuk receive booking notification)
✓ Or email logs enabled (untuk verify email attempt)
```

---

## 🔧 SETUP OPTIONS

### OPTION 1: Fresh Database with Seeder (Recommended)

**Pros**: Clean, reproducible, no garbage data
**Cons**: Need working seeder

**Steps**:
```bash
# 1. Fresh database
php artisan migrate:fresh

# 2. Seed with factory data
php artisan db:seed

# 3. Verify
php artisan tinker
>>> User::count()  # Should show > 0

# 4. Create test booking manually or via seeder
```

### OPTION 2: Manual SQL Insert

**Pros**: Full control, no seeder needed
**Cons**: Manual, error-prone

**Steps**:
```sql
-- 1. Admin user
INSERT INTO users (name, email, password, role, email_verified_at, created_at) VALUES 
('Admin Satpals', 'admin@test.com', '$2y$12$...', 'super_admin', NOW(), NOW());

-- 2. Studio records
INSERT INTO studios (name, price_per_hour, capacity, description, created_at) VALUES
('Studio A - Recording', 150000, 10, 'Professional recording studio', NOW()),
('Studio B - Rehearsal', 100000, 15, 'Rehearsal space with drums + guitar', NOW());

-- 3. Sample users with different roles
INSERT INTO users (name, email, password, role, created_at) VALUES
('Ahmad Ketua', 'ketua@test.com', '$2y$12$...', 'ketua_umum', NOW()),
('Budi Wakil', 'wakil@test.com', '$2y$12$...', 'wakil_ketua_umum', NOW()),
('Citra Board', 'board1@test.com', '$2y$12$...', 'board_member', NOW()),
('Dian Board', 'board2@test.com', '$2y$12$...', 'board_member', NOW()),
('Eka Board', 'board3@test.com', '$2y$12$...', 'board_member', NOW());

-- 4. Pending Studio Booking (untuk approve demo)
INSERT INTO studio_bookings (booking_code, renter_name, renter_email, renter_phone, 
    studio_id, booking_scope, number_of_ukm, booking_date, duration_hours, status, created_at) VALUES
('STB-2605-0001', 'Kelompok Musik IT', 'music@it.com', '087654321098', 
    1, 'ukm_all', 8, DATE_ADD(NOW(), INTERVAL 3 DAY), 2, 'pending', NOW());

-- 5. Some approved bookings (untuk keuangan demo)
INSERT INTO studio_bookings (booking_code, renter_name, renter_email, 
    studio_id, booking_scope, number_of_ukm, booking_date, duration_hours, 
    price_per_hour, total_price, status, approved_at, created_at) VALUES
('STB-2605-0002', 'Student Club A', 'club@test.com', 
    1, 'ukm_all', 6, DATE_SUB(NOW(), INTERVAL 5 DAY), 3, 
    150000, 450000, 'approved', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('STB-2605-0003', 'Student Club B', 'clubb@test.com', 
    2, 'non_ukm', 2, DATE_SUB(NOW(), INTERVAL 2 DAY), 2, 
    100000, 200000, 'approved', DATE_SUB(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY));

-- 6. Income records (untuk financial integration)
INSERT INTO incomes (booking_code, description, amount, status, created_by, created_at) VALUES
('STB-2605-0002', 'Studio Booking - Student Club A', 450000, 'approved', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('STB-2605-0003', 'Studio Booking - Student Club B', 200000, 'approved', 1, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- 7. Optional: Some expense records
INSERT INTO expenses (description, amount, expense_type, status, created_by, created_at) VALUES
('Rental Equipment', 500000, 'equipment', 'approved', 1, DATE_SUB(NOW(), INTERVAL 10 DAY));
```

### OPTION 3: Existing Database - Clean Data

**Pros**: Keep development data
**Cons**: Might have garbage data

**Steps**:
```bash
# 1. Keep existing database, just verify test data exists

# 2. Check if admin account exists
php artisan tinker
>>> User::where('email', 'admin@test.com')->first()

# 3. If not, create one
>>> User::create([
    'name' => 'Admin Satpals',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'super_admin',
    'email_verified_at' => now()
])

# 4. Verify bookings exist
>>> StudioBooking::where('status', 'pending')->first()

# 5. If not enough data, manually add test data
```

---

## 🎯 MINIMUM DATA REQUIREMENTS

### For Each Demo Phase:

#### Phase 1: Public Booking Form
```
Required:
✓ At least 1 studio record (untuk show dalam form)

To Create New Booking:
- Database ready to INSERT new studio_booking record
- No validation error
```

#### Phase 2: Admin Dashboard
```
Required:
✓ Admin account exists dan dapat login
✓ At least 1 pending booking (untuk counter)
✓ At least 1 approved booking (untuk income count)

For "Menunggu Approval" Counter:
- Should show: (pending expenses) + (pending incomes)
- Example: If 2 pending expense + 1 pending income = counter shows 3
```

#### Phase 3: Approval Workflow
```
Required:
✓ At least 1 pending booking created in Phase 1
✓ Email configuration working (or logs accessible)
✓ Storage directory writable (untuk PDF generation)
```

#### Phase 4: Financial Dashboard
```
Required:
✓ At least 2-3 income records dari bookings
✓ Preferably mix of different booking types
✓ Both current month and previous month untuk filter demo
```

#### Phase 5: User Search
```
Required:
✓ At least 5-10 users dalam database
✓ Users dengan role:
  - ketua_umum (at least 1)
  - wakil_ketua_umum (at least 1)
  - board_member (at least 2)
  - super_admin (admin account)
  - public user (optional)
```

---

## 🗂️ SQL BACKUP - QUICK COPY PASTE

### Setup Script (All-in-One)
```sql
-- SATPALS Demo Database Setup Script
-- Paste ini ke MySQL command line atau DB tool

-- 1. Admin User
INSERT INTO users (id, name, email, password, role, email_verified_at, created_at, updated_at) 
VALUES (1, 'Admin Satpals', 'admin@test.com', '$2y$12$VZt.b6vKFExT8M7xJnSyWO3zKiLIAZwCmlxyEUcLp2B7C5XfKLDU.', 'super_admin', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 2. Studio Records
INSERT INTO studios (name, capacity, price_per_hour, description, created_at, updated_at) 
VALUES 
('Studio A - Recording', 10, 150000, 'Professional recording studio', NOW(), NOW()),
('Studio B - Rehearsal', 15, 100000, 'Rehearsal space with drums', NOW(), NOW())
ON DUPLICATE KEY UPDATE price_per_hour=VALUES(price_per_hour);

-- 3. Board Members / Users with Roles
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) 
VALUES 
('Ahmad Sudradjat', 'ketua@test.com', '$2y$12$...(same hash)', 'ketua_umum', NOW(), NOW(), NOW()),
('Budi Hermawan', 'wakil@test.com', '$2y$12$...(same hash)', 'wakil_ketua_umum', NOW(), NOW(), NOW()),
('Citra Dewi', 'board1@test.com', '$2y$12$...(same hash)', 'board_member', NOW(), NOW(), NOW()),
('Dian Fitrah', 'board2@test.com', '$2y$12$...(same hash)', 'board_member', NOW(), NOW(), NOW()),
('Eka Pratama', 'board3@test.com', '$2y$12$...(same hash)', 'board_member', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 4. Pending Studio Booking (untuk demo approve)
INSERT INTO studio_bookings 
(booking_code, renter_name, renter_email, renter_phone, studio_id, 
 booking_scope, number_of_ukm, booking_date, duration_hours, status, created_at, updated_at) 
VALUES 
('STB-2605-DEMO', 'Kelompok Musik IT', 'music@it.com', '087654321098', 1, 
 'ukm_all', 8, DATE_ADD(NOW(), INTERVAL 3 DAY), 2, 'pending', NOW(), NOW());

-- 5. Approved Bookings (untuk financial dashboard demo)
INSERT INTO studio_bookings 
(booking_code, renter_name, renter_email, studio_id, booking_scope, 
 number_of_ukm, booking_date, duration_hours, price_per_hour, total_price, 
 status, approved_at, created_at, updated_at) 
VALUES 
('STB-2605-001', 'Club UKM A', 'club.a@test.com', 1, 'ukm_all', 
 6, DATE_SUB(NOW(), INTERVAL 3 DAY), 3, 150000, 450000, 
 'approved', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
('STB-2605-002', 'Club UKM B', 'club.b@test.com', 2, 'non_ukm', 
 2, DATE_SUB(NOW(), INTERVAL 1 DAY), 2, 100000, 200000, 
 'approved', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- 6. Income Records (untuk financial integration)
INSERT INTO incomes 
(booking_code, description, amount, status, created_by, created_at, updated_at) 
VALUES 
('STB-2605-001', 'Studio Booking - Club UKM A', 450000, 'approved', 1, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
('STB-2605-002', 'Studio Booking - Club UKM B', 200000, 'approved', 1, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- 7. Expense Records (optional - untuk show complete financial picture)
INSERT INTO expenses 
(description, amount, expense_type, status, created_by, created_at, updated_at) 
VALUES 
('Rental Proyektor', 500000, 'equipment', 'approved', 1, NOW(), NOW()),
('Pembelian Kabel Audio', 250000, 'equipment', 'pending', 1, NOW(), NOW());

-- Verify Data
SELECT '=== VERIFICATION ===' AS status;
SELECT COUNT(*) as admin_count FROM users WHERE role='super_admin';
SELECT COUNT(*) as board_count FROM users WHERE role LIKE '%ketua%' OR role='board_member';
SELECT COUNT(*) as studio_count FROM studios;
SELECT COUNT(*) as pending_booking FROM studio_bookings WHERE status='pending';
SELECT COUNT(*) as approved_booking FROM studio_bookings WHERE status='approved';
SELECT COUNT(*) as income_count FROM incomes;
SELECT COUNT(*) as expense_count FROM expenses;
```

**Note**: Change password hash to your own using:
```bash
php artisan tinker
>>> bcrypt('password')
# Output: $2y$12$....yourHashHere
```

---

## 🔐 Password Hash Reference

For test accounts, use password: `password`

**Hash** (pre-computed):
```
$2y$12$VZt.b6vKFExT8M7xJnSyWO3zKiLIAZwCmlxyEUcLp2B7C5XfKLDU.
```

Or generate fresh:
```bash
php artisan tinker
>>> bcrypt('password')
```

---

## 📊 DATA VOLUME GUIDE

### Minimal (Works, but thin)
```
- 1 admin user
- 1-2 studios
- 1 pending booking
- 2 approved bookings + 2 incomes
- 5 users (different roles)
```

### Comfortable (Looks good)
```
- 1 admin user
- 2-3 studios
- 1-2 pending bookings
- 5+ approved bookings + 5+ incomes
- 8-10 users (mix of roles)
- 2-3 expenses
```

### Comprehensive (Professional looking)
```
- 1 admin user
- 3-5 studios
- 1-3 pending bookings
- 10+ approved bookings + incomes
- 15+ users (various roles, board structure)
- 5+ expenses
- Band rental data (optional)
- Equipment rental data (optional)
```

---

## ⚠️ COMMON DATA ISSUES

### Issue: Admin Login Fails
**Solution**:
```sql
SELECT * FROM users WHERE email='admin@test.com';
-- If empty:
INSERT INTO users (name, email, password, role, email_verified_at, created_at) 
VALUES ('Admin', 'admin@test.com', '$2y$12$VZt...', 'super_admin', NOW(), NOW());
```

### Issue: Dashboard Counter Wrong
**Check**:
```sql
SELECT COUNT(*) FROM expenses WHERE status='pending';
SELECT COUNT(*) FROM incomes WHERE status='pending';
-- Sum should equal "Menunggu Approval" counter
```

### Issue: Booking Form Not Submitting
**Check**:
```sql
SELECT * FROM studios;
-- Must have at least 1 record
-- Check if studio capacity/price exists
```

### Issue: Financial Dashboard Empty
**Check**:
```sql
SELECT * FROM incomes WHERE status='approved';
-- Must have approved income records
-- Check if associated with bookings
```

### Issue: User Search Not Working
**Check**:
```sql
SELECT name, email, role FROM users;
-- Should have multiple users with different roles
-- Check role names (underscore format in DB)
```

---

## 🔄 PRE-DEMO REFRESH SCRIPT

**After demo or testing, use this to prepare fresh state:**

```bash
# Option 1: Completely fresh database
php artisan migrate:fresh --seed

# Option 2: Clear only transactional data (keep setup)
php artisan tinker

# In tinker:
>>> StudioBooking::truncate();
>>> Income::truncate();
>>> Expense::truncate();
>>> exit;

# Then re-insert test data with SQL script above
```

---

## 📝 TEST DATA CHECKLIST

Before starting presentation:

```
□ Admin account login works (admin@test.com / password)
□ At least 1 studio exists
□ At least 1 pending booking exists
□ At least 2 approved bookings exist
□ At least 2 income records exist (approved)
□ At least 5 users with different roles exist
□ Email notification recipient ready (music@it.com or logs)
□ Database has no error/corruption
□ Backup copy of database ready (just in case)
□ Clear test data (no garbage records)
```

---

**Last Updated**: May 22, 2026  
**For**: Demo Presentasi Semhas  
**Status**: Ready to Use
