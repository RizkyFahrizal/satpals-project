# 👤 User Pengurus - Login Account System

## 📊 Overview

Semua 18 pengurus UKM Satya Palapa sekarang memiliki **user account untuk login ke admin panel**.

---

## ✅ User Pengurus yang Sudah Dibuat

| # | Nama | Username | Jabatan | Password |
|---|------|----------|---------|----------|
| 1 | Hylmi Tri Widiastoro | hylmi_tri_widiastoro | Ketua Umum | hylmitriwidiastoro |
| 2 | Mirza Ramadian Raffa | mirza_ramadian_raffa | Wakil Ketua Umum | mirzaramadianraffa |
| 3 | Nurmalinda Rista Widya | nurmalinda_rista_widya | Sekretaris | nurmalindaristawidya |
| 4 | Isna Nur Rahmawati | isna_nur_rahmawati | Bendahara | isnanurrahmawati |
| 5 | Nabila Aliya Khoirunnisa | nabila_aliya_khoirunnisa | Ketua MPA | nabilaaliyakhoirunnisa |
| 6 | CHOIRUL WAHYU ADJI | choirul_wahyu_adji | Subsie Band | chorirulwahyuadji |
| 7 | Ibe Zisokhi Laia | ibe_zisokhi_laia | Subsie Band | ibezisokhlaia |
| 8 | Muhammad Bintang Naufal | muhammad_bintang_naufal | Subsie Band | muhammadbtangnaufal |
| 9 | Bagas Kusuma Pranata | bagas_kusuma_pranata | Subsie Band | bagaskusumapranata |
| 10 | Sulthan Wahyu Atmojo | sulthan_wahyu_atmojo | Subsie Peralatan | sulthanuahyuatmojo |
| 11 | Muhammad Riski Anandio | muhammad_riski_anandio | Subsie Peralatan | muhammadriski_anandio |
| 12 | Theopilus Sinuraya | theopilus_sinuraya | Subsie Peralatan | theopilussinuraya |
| 13 | Cleo Firman Ferdinand | cleo_firman_ferdinand | Subsie Humas | cleofirmanferdinand |
| 14 | Fachry Akbar Putra Ediesthia | fachry_akbar_putra_ediesthia | Subsie Humas | fachryakbarputraediesthia |
| 15 | Michael Gideon Artanarga Pakpahan | michael_gideon_artanarga_pakpa | Subsie PDD | michaelgideonartanargapakpahan |
| 16 | Satria Alanku Yudhita Putra | satria_alanku_yudhita_putra | Subsie PDD | satriaalanku_yudita_putra |
| 17 | Nova Amalia Nohara | nova_amalia_nohara | Subsie Kesekretariatan | novaameliaahnohara |
| 18 | Naela Shafa Rania Putri | naela_shafa_rania_putri | Subsie Kesekretariatan | naelashafraniaputri |

---

## 🔐 Cara Login Pengurus

### Login URL
```
http://localhost:8000/login
```

### Format Kredensial

**Username**: lowercase nama_lengkap dengan underscore
```
Contoh: "Hylmi Tri Widiastoro" → hylmi_tri_widiastoro
```

**Password**: lowercase nama_lengkap tanpa spasi
```
Contoh: "Hylmi Tri Widiastoro" → hylmitriwidiastoro
```

### Contoh Login
```
Username: hylmi_tri_widiastoro
Password: hylmitriwidiastoro
```

---

## 🛡️ Security Notes

### ⚠️ PENTING: First Login

Setiap pengurus **HARUS reset password** saat first login karena:
- Password di-generate otomatis untuk testing
- Password tidak aman untuk production
- Admin harus set password yang kuat

### Password Requirements
- Minimum 8 karakter
- Mix huruf besar dan kecil
- Include angka & special char (recommended)
- Tidak boleh sama dengan username

### Account Status
```
✓ Semua user adalah ACTIVE (is_active = true)
✓ Email format: username@satya-palapa.local
✓ Role: 'pengurus'
✓ Linked ke board_member via user_id
```

---

## 📝 Database Structure

### Users Table (New Columns)
```sql
ALTER TABLE users ADD COLUMN username VARCHAR(255) UNIQUE;
ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT true;
```

### Relationship
```
User (1) ─→ (1) BoardMember
  ├─ user_id (FK)
  └─ can manage board members in admin panel
```

### Data Sample
```json
{
  "id": 1,
  "name": "Hylmi Tri Widiastoro",
  "email": "hylmi_tri_widiastoro@satya-palapa.local",
  "username": "hylmi_tri_widiastoro",
  "password": "hashed_bcrypt...",
  "role": "pengurus",
  "is_active": true,
  "created_at": "2026-03-25T00:00:00Z",
  "updated_at": "2026-03-25T00:00:00Z"
}
```

---

## 🔧 Admin Panel - Pengurus Access

### Yang Bisa Diakses Pengurus

**READ:**
- ✅ Lihat struktur pengurus (detail page untuk diri sendiri)
- ✅ Lihat foto & bio pengurus lain (limited)
- ✅ Dashboard overview

**LIMITED:**
- ⚠️ Edit profile sendiri (foto, bio)
- ⚠️ Reset password sendiri

**NOT ALLOWED:**
- ❌ Tambah/edit/hapus pengurus lain
- ❌ Manage member
- ❌ Manage aktivitas
- ❌ Admin settings

### Akses Admin
Super admin masih bisa:
- ✅ Full CRUD pengurus
- ✅ Manage user accounts
- ✅ Reset password pengurus
- ✅ Activate/deactivate users

---

## 🔐 Cara Reset Password di Admin

### Sebagai Super Admin:

```
Admin Panel → User Management (if exists) → Pilih pengurus → Reset Password
```

**Atau via CLI:**
```bash
php artisan tinker
# Inside tinker:
$user = App\Models\User::where('username', 'hylmi_tri_widiastoro')->first();
$user->update(['password' => Hash::make('NewPassword123!')]);
```

---

## 📋 Troubleshooting

### ❓ Login Gagal?

**Check 1: Username Benar?**
- Username format: lowercase, underscore, no special char
- Contoh: `hylmi_tri_widiastoro` (bukan `Hylmi Tri Widiastoro`)

**Check 2: Password Benar?**
- Password: lowercase nama tanpa spasi
- Contoh: `hylmitriwidiastoro`
- Case-sensitive!

**Check 3: User Aktif?**
```bash
php artisan tinker
# Inside:
$user = App\Models\User::where('username', 'username_here')->first();
echo $user->is_active; // Should be true (1)
```

### ❓ Lupa Password?

Admin bisa reset:
```bash
php artisan tinker
$user = App\Models\User::where('username', 'hylmi_tri_widiastoro')->first();
$user->update(['password' => Hash::make('hylmitriwidiastoro')]); // Reset ke default
echo "Password reset to default";
```

### ❓ User Tidak Bisa Login?

**Possible Causes:**
1. `is_active = false` → Activate di DB
2. Password salah → Reset password
3. User tidak ada di DB → Create via seeder
4. Email sudah dipakai → Check unique constraint

---

## 🔄 Generate Lebih Banyak User

Jika perlu tambah pengurus baru dengan user account:

### Manual via Admin Panel:
1. Buka `/admin/board`
2. Klik "Tambah Pengurus"
3. Cek checkbox "Buat Akun Login"
4. Submit form
5. User account otomatis dibuat

### Via Artisan (Advanced):
```bash
php artisan tinker
$user = App\Models\User::create([
    'name' => 'Nama Pengurus Baru',
    'username' => 'nama_pengurus_baru',
    'email' => 'nama_pengurus_baru@satya-palapa.local',
    'password' => Hash::make('namapengurusbaru'),
    'role' => 'pengurus',
    'is_active' => true,
]);
```

---

## 📊 Stats

| Metric | Value |
|--------|-------|
| Total User Pengurus | 18 |
| All Active | ✅ Yes |
| All with username | ✅ Yes |
| All linked to BoardMember | ✅ Yes |
| Role | pengurus |
| Email Domain | satya-palapa.local |

---

## 🚀 Next Steps

1. **Distribute Credentials** - Share login info ke masing-masing pengurus
2. **First Login** - Semua pengurus HARUS reset password
3. **Profile Setup** - Upload foto & lengkapi bio (jika ada)
4. **Welcome Email** (Optional) - Kirim email welcome dengan login info

---

## 📞 Support

Untuk masalah user account:
1. Check database langsung: `php artisan tinker`
2. Review migration: `2026_03_25_000001_add_username_and_is_active_to_users_table.php`
3. Review seeder: `BoardMemberUserSeeder.php`
4. Check logs: `storage/logs/laravel.log`
