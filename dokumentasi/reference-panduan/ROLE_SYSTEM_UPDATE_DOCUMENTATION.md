# 🔐 Sistem Role Pengurus - Update Dokumentasi

## 📋 Ringkasan Perubahan

Sistem role telah diperbarui dari role generik ("admin" dan "pengurus") menjadi role spesifik yang sesuai dengan jabatan di struktur pengurus UKM.

### Role Lama
- `super_admin` - Super Admin (tidak berubah)
- `pengurus` - Pengurus (generic)
- `public` - User Biasa (tidak berubah)

### Role Baru
- `super_admin` - Super Admin (tidak berubah)
- `ketua_umum` - Ketua Umum
- `wakil_ketua_umum` - Wakil Ketua Umum
- `bendahara` - Bendahara
- `sekretaris` - Sekretaris
- `mpa` - Ketua Majelis Perwakilan Anggota (MPA)
- `band` - Subsie Band
- `peralatan` - Subsie Peralatan
- `humas` - Subsie Humas (Public Relations)
- `pdd` - Subsie Produksi dan Dokumentasi
- `kesekretariatan` - Subsie Kesekretariatan
- `public` - User Biasa (tidak berubah)

---

## 🔄 Perubahan pada File

### 1. **Database Migration**
**File:** `database/migrations/2026_05_09_154917_change_user_role_enum_to_specific_roles.php`

Mengubah enum column `role` pada tabel `users` untuk mendukung 12 role baru.

```sql
-- Sebelum:
ENUM('super_admin', 'pengurus', 'public')

-- Sesudah:
ENUM('super_admin', 'public', 'ketua_umum', 'wakil_ketua_umum', 'bendahara', 'sekretaris', 'mpa', 'band', 'peralatan', 'humas', 'pdd', 'kesekretariatan')
```

**Cara Run:** `php artisan migrate`

---

### 2. **Model User** 
**File:** `app/Models/User.php`

#### Tambahan Constants
```php
const ROLE_KETUA_UMUM = 'ketua_umum';
const ROLE_WAKIL_KETUA_UMUM = 'wakil_ketua_umum';
const ROLE_BENDAHARA = 'bendahara';
const ROLE_SEKRETARIS = 'sekretaris';
const ROLE_MPA = 'mpa';
const ROLE_BAND = 'band';
const ROLE_PERALATAN = 'peralatan';
const ROLE_HUMAS = 'humas';
const ROLE_PDD = 'pdd';
const ROLE_KESEKRETARIATAN = 'kesekretariatan';
```

#### Tambahan Methods
```php
public static function getBoardMemberRoles(): array
    // Return semua role pengurus (excluding public & super_admin)

public static function getBoardMemberRolesWithoutKetuaUmum(): array
    // Return semua role pengurus KECUALI ketua_umum (untuk struktur pengurus form)
```

#### Updated Methods
- `isPengurus()` - Sekarang check jika user adalah board member (any role)
- `isBoardMember()` - Method baru, sama fungsi dengan isPengurus()
- `hasAdminAccess()` - Sekarang support semua board member roles
- `getRoleLabelAttribute()` - Support semua role baru

---

### 3. **Model BoardMember**
**File:** `app/Models/BoardMember.php`

#### Tambahan Method
```php
public static function jabatanToRole($jabatan): string
    // Map jabatan ke role user
    // Contoh: 'subsie_band' => 'band'
```

#### Updated Method: `createUserAccount()`
Sekarang assign role sesuai jabatan pengurus, bukan hardcode `'pengurus'`.

```php
// Sebelum:
'role' => 'pengurus'

// Sesudah:
'role' => self::jabatanToRole($this->jabatan)
```

---

### 4. **Controller: UserController**
**File:** `app/Http/Controllers/UserController.php`

#### Updated Methods
- `store()` - Support all board member roles saat create user
- `update()` - Support all board member roles saat edit user
- `updateRole()` - Support all board member roles saat ubah role

Semua method sekarang menggunakan `User::getBoardMemberRoles()` daripada hardcode role.

---

### 5. **Controller: BoardMemberController**
**File:** `app/Http/Controllers/Admin/BoardMemberController.php`

#### Updated Method: `store()`
Validasi jabatan sekarang **exclude `ketua_umum`**:

```php
'jabatan' => 'required|string|not_in:ketua_umum'
```

**Alasan:** Ketua Umum hanya bisa ditambahkan dari menu Admin oleh Super Admin, tidak dari menu Struktur Pengurus.

---

### 6. **Blade Views**

#### a. `resources/views/admin/users/create.blade.php`
- Changed dari hardcoded 2 radio buttons ke loop yang display semua board member roles
- Ditambahkan scroll pada container karena banyak opsi

#### b. `resources/views/admin/users/edit.blade.php`
- Same as create.blade.php
- Loop display all available roles

#### c. `resources/views/admin/board/index.blade.php`
- Jabatan dropdown sekarang exclude `ketua_umum`
- Ditambahkan note: "*Ketua Umum hanya dapat ditambahkan dari menu Admin"
- User akan melihat hanya 9 opsi (bukan 10) saat tambah pengurus via struktur pengurus form

---

### 7. **Seeder: MigratePengurusRolesToSpecificRolesSeeder**
**File:** `database/seeders/MigratePengurusRolesToSpecificRolesSeeder.php`

Seeder baru untuk migrate existing users dengan role `'pengurus'` ke role spesifik berdasarkan jabatan board member mereka.

**Cara Run:**
```bash
php artisan db:seed --class=MigratePengurusRolesToSpecificRolesSeeder
```

**Logika:**
1. Find all users with role `'pengurus'`
2. Find board member terkait
3. Map jabatan ke role baru
4. Update user role

Jika user tidak punya board member, assign default role `'kesekretariatan'`.

---

### 8. **Routes**
**File:** `routes/web.php`

Updated comment dari:
```php
// Admin Routes (Protected - Only super_admin and pengurus)
```

Menjadi:
```php
// Admin Routes (Protected - Only super_admin and specific board member roles)
```

---

## ✅ Proses Implementasi

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Run Seeder (untuk migrate existing pengurus)
```bash
php artisan db:seed --class=MigratePengurusRolesToSpecificRolesSeeder
```

### Step 3: Test
- Login sebagai super admin ke `/admin`
- Test create user baru dengan berbagai role
- Test edit user role
- Test create pengurus dari menu Struktur Pengurus - pastikan ketua_umum tidak ada di dropdown
- Test create login saat add pengurus - pastikan role otomatis sesuai jabatan

---

## 📊 Impact Analysis

### Affected Modules
- ✅ User Management (Admin Panel)
- ✅ Struktur Pengurus (Board Member Management)
- ✅ Authorization & Middleware
- ✅ Login System
- ✅ Dashboard Access Control

### Backward Compatibility
- ⚠️ **Breaking Change**: Role `'pengurus'` tidak lagi valid
- ✅ Users created sebelumnya akan automatically migrated via seeder
- ✅ Super Admin role tetap unchanged
- ✅ Public role tetap unchanged
- ✅ Middleware & helpers sudah support new roles

### Database Change
- Migration: Change ENUM type untuk role column
- Seeder: Migrate data from old role to new roles

---

## 🔒 Security Notes

1. **Ketua Umum Role**
   - Hanya bisa ditambahkan dari menu Admin (super admin)
   - Tidak bisa ditambahkan dari menu Struktur Pengurus
   - Validasi di controller level: `'jabatan' => 'not_in:ketua_umum'`

2. **Role Assignment**
   - Automatic: Saat create account di Struktur Pengurus, role assign sesuai jabatan
   - Manual: Super admin bisa manually assign/change role di menu Admin Users

3. **Authorization**
   - Middleware `admin.access` masih sama, cek `hasAdminAccess()`
   - Method ini sudah updated untuk support semua board member roles

---

## 📝 Testing Checklist

- [ ] Migration runs without errors
- [ ] Seeder runs and migrates existing users
- [ ] Super admin dapat create user dengan semua roles
- [ ] Super admin dapat edit user role
- [ ] Struktur pengurus form tidak show ketua_umum option
- [ ] Saat create account di struktur pengurus, role otomatis sesuai jabatan
- [ ] Login tetap berfungsi untuk semua role
- [ ] Admin dashboard accessible untuk semua board member roles
- [ ] Public role tetap work seperti sebelumnya
- [ ] No SQL errors di log files

---

## 🚀 Rollback Strategy

Jika perlu rollback:

```bash
# Rollback migration
php artisan migrate:rollback

# Users akan kembali ke enum yang lama dengan role tidak valid
# Harus restore dari backup atau manual update
```

**Recommended:** Backup database sebelum run migration.

---

## 📞 Support

Jika ada masalah:
1. Check migration status: `php artisan migrate:status`
2. Check user roles: `select distinct(role) from users;`
3. Check seeder output saat run
4. Check error logs: `storage/logs/laravel.log`

