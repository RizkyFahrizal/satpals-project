# 🎯 Panduan Pengelolaan Pengurus - Quick Reference

## Siapa Bisa Apa?

### 👨‍💼 Super Admin
**Akses**: ✅ Penuh (semua periode, semua operasi)
- ✅ Tambah pengurus di struktur pengurus
- ✅ Edit pengurus di struktur pengurus
- ✅ Hapus pengurus di struktur pengurus
- ✅ **Tambah Ketua Umum** (hanya di Admin > Users)
- ✅ Ubah role siapapun

**Cara Membuat Ketua Umum Baru:**
1. Buka **Admin > Users > Tambah User**
2. Pilih anggota dari dropdown
3. Pilih role **"Ketua Umum"**
4. Input password & buat akun
5. Ketua Umum siap login & manage pengurus

### 👑 Ketua Umum (Aktif di Periode Ini)
**Akses**: ✅ Terima periode mereka aktif saja
- ✅ Tambah pengurus
- ✅ Edit pengurus
- ✅ Hapus pengurus

**Periode yang Berbeda:**
- ❌ Tidak bisa manage pengurus di periode lain
- ⚠️ Warning: "Hanya Ketua/Wakil/MPA yang aktif dapat menambah pengurus"

### 👥 Wakil Ketua Umum & MPA (Aktif di Periode Ini)
**Akses**: ✅ Sama seperti Ketua Umum
- ✅ Tambah pengurus (jika aktif di periode ini)
- ✅ Edit pengurus (jika aktif di periode ini)
- ✅ Hapus pengurus (jika aktif di periode ini)

### 💼 Bendahara & Role Lain
**Akses**: ❌ Tidak bisa manage pengurus
- ❌ Tidak bisa tambah
- ❌ Tidak bisa edit
- ❌ Tidak bisa hapus
- ⚠️ Tombol "Tambah Pengurus" tidak terlihat

---

## 📋 Proses Kerja

### Tambah Pengurus Baru

**Dilakukan Oleh**: Super Admin, Ketua/Wakil/MPA (aktif)

**Langkah:**
1. Buka menu **Struktur Pengurus**
2. Pilih periode dari dropdown (top kanan)
3. Klik tombol **"Tambah Pengurus"** (kalau ada)
   - Jika tidak ada → Anda tidak authorized
4. Isi form:
   - **Periode**: Otomatis sesuai periode yang dipilih
   - **Anggota**: Pilih dari dropdown (anggota aktif yang belum punya posisi)
   - **Jabatan**: Pilih (TAPI **TIDAK BOLEH** Ketua Umum - ini hanya dari Admin > Users)
   - **Foto**: Upload (optional)
   - **Buat Akun Login**: Centang jika ingin langsung buat akun
5. Klik **"Simpan"**
6. Selesai! Pengurus berhasil ditambah

### Edit Pengurus

**Dilakukan Oleh**: Super Admin, Ketua/Wakil/MPA (aktif, periode sama)

**Langkah:**
1. Buka menu **Struktur Pengurus**
2. Pilih periode yang tepat
3. Temukan card pengurus yang ingin di-edit
4. Klik tombol **"Edit"** (pensil icon)
5. Update data yang perlu diubah
6. Klik **"Simpan Perubahan"**
7. Selesai!

### Hapus Pengurus

**Dilakukan Oleh**: Super Admin, Ketua/Wakil/MPA (aktif, periode sama)

**Langkah:**
1. Buka menu **Struktur Pengurus**
2. Pilih periode yang tepat
3. Temukan card pengurus yang ingin dihapus
4. Klik tombol **"Hapus"** (trash icon)
5. Konfirmasi penghapusan
6. Selesai! Pengurus dihapus dari struktur

### Buat Ketua Umum Baru

**HANYA Dilakukan Oleh**: Super Admin

**Langkah:**
1. Buka menu **Admin > Users**
2. Klik **"Tambah User"**
3. Isi form:
   - **Anggota**: Pilih siapa yang akan jadi Ketua
   - **Role**: Pilih **"Ketua Umum"**
   - **Password**: Input password
4. Klik **"Simpan"**
5. Ketua siap login & manage pengurus

**Catatan**: 
- Tidak bisa di menu Struktur Pengurus
- Menu Struktur hanya untuk manage subsie & wakil-wakil
- Ketua adalah special role yang harus dibuat dari Admin

---

## ⚠️ Troubleshooting

### Q: Kenapa tombol "Tambah Pengurus" tidak terlihat?
**A**: Kemungkinan:
1. Anda bukan Super Admin, Ketua, Wakil, atau MPA
2. Anda adalah Ketua/Wakil/MPA tapi tidak aktif di periode saat ini
3. Browser cache perlu di-clear (F5 atau Ctrl+Shift+R)

**Solusi**: Hubungi Super Admin untuk assign role yang tepat

### Q: Kenapa tidak bisa add Ketua Umum dari menu Struktur Pengurus?
**A**: By design! Ketua Umum adalah role special yang hanya bisa dibuat dari Admin > Users oleh Super Admin.

**Solusi**: 
- Jika perlu tambah Ketua baru → Minta Super Admin
- Super Admin buka Admin > Users > Tambah > Pilih role "Ketua Umum"

### Q: Kenapa bisa add pengurus di periode lalu tapi tidak di periode sekarang?
**A**: Karena Anda hanya aktif di periode lalu. Ketua/Wakil/MPA hanya bisa manage pengurus di periode mereka aktif.

**Solusi**: 
- Jika perlu manage periode berbeda → Login dengan akun yang aktif di periode tersebut
- Atau → Minta Super Admin (bisa di semua periode)

### Q: Akun login Ketua tidak bisa, padahal sudah dibuat?
**A**: Kemungkinan:
1. Password ketik salah
2. Email belum di-verify (jika requirement ada)
3. Akun belum di-activate

**Solusi**: Hubungi Super Admin, minta reset password atau check status akun

---

## 📍 Menu Locations

```
ADMIN PANEL
├── Dashboard
├── Anggota (Data Members)
├── Struktur Pengurus ← Kelola pengurus di sini
│   ├── Periode selector (top)
│   ├── Tombol "Tambah Pengurus" (jika authorized)
│   ├── Cards pengurus (dengan edit/hapus buttons)
│   └── Edit & Delete modals
├── Users ← Buat Ketua Umum di sini!
│   ├── List Users
│   └── Tambah User (select role "Ketua Umum")
└── ... menu lainnya
```

---

## 🔒 Sistem Keamanan

**Bagaimana sistem memastikan hanya authorized users yang bisa manage pengurus?**

1. **UI Level**: Tombol hanya muncul untuk authorized users
   - Better UX, tidak membingungkan
   - Tombol tidak terlihat = tidak bisa diakses

2. **Backend Level** (lebih penting!): 
   - Setiap request (add/edit/delete) di-check di server
   - User tidak authorized = request ditolak
   - Bahkan jika user "hack" frontend, backend tetap protect

3. **Database Level**:
   - Data hanya disimpan jika authorization check pass
   - Audit trail bisa ditambah (siapa edit kapan)

**Kesimpulan**: Sistem ini secure dari berbagai angle ✅

---

## 📞 Support

**Ada pertanyaan atau masalah?**

1. Check FAQ di atas
2. Hubungi Super Admin
3. Baca dokumentasi lengkap di `BOARD_MEMBER_AUTHORIZATION.md`

---

**Last Updated**: May 10, 2026
**Version**: 1.0 - User Friendly Edition
