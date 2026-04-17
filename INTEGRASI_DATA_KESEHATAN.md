# Integrasi Data Kesehatan dari Diklat Registration ke Members

## Ringkasan Perubahan

Data untuk 3 kolom baru pada tabel `members` sekarang otomatis diambil dari tabel `diklat_registrations` ketika pendaftar diklat disetujui dan dikonversi menjadi anggota UKM.

### Kolom yang Terintegrasi:
1. **riwayat_penyakit** - Otomatis dari `diklat_registrations.riwayat_penyakit`
2. **riwayat_alergi** - Otomatis dari `diklat_registrations.riwayat_alergi`  
3. **no_telepon_ortu** - Otomatis dari `diklat_registrations.no_telepon_ortu`

---

## File yang Diubah

### 1. Model Member (`app/Models/Member.php`)
**Method: `createFromDiklatRegistration()`**

Sebelumnya, method ini hanya mengambil data dasar. Sekarang juga mengambil 3 kolom kesehatan:

```php
public static function createFromDiklatRegistration(DiklatRegistration $registration): self
{
    return self::create([
        'diklat_registration_id' => $registration->id,
        'diklat_period_id' => $registration->diklat_period_id,
        'nama_lengkap' => $registration->nama_lengkap,
        'jenis_kelamin' => $registration->jenis_kelamin,
        'no_telepon' => $registration->no_telepon_pribadi,
        'no_telepon_ortu' => $registration->no_telepon_ortu,  // ← BARU
        'npm' => $registration->npm,
        'fakultas' => $registration->fakultas,
        'prodi' => $registration->prodi,
        'spesifikasi' => $registration->spesifikasi,
        'spesifikasi_lainnya' => $registration->spesifikasi_lainnya,
        'riwayat_penyakit' => $registration->riwayat_penyakit,  // ← BARU
        'riwayat_alergi' => $registration->riwayat_alergi,      // ← BARU
        'angkatan' => $registration->period?->tahun_masuk ?? now()->year,
        'status' => self::STATUS_AKTIF,
    ]);
}
```

---

## Alur Penggunaan

### Skenario 1: Pendaftar Diklat Disetujui
```
User mengisi form pendaftaran diklat
    ↓
Admin melihat data pendaftar di halaman Kelola Diklat
    ↓
Admin click "Terima" atau mengubah status → "Diterima"
    ↓
System otomatis memanggil: Member::createFromDiklatRegistration()
    ↓
Member dibuat dengan data kesehatan yang sudah terisi dari diklat registration
```

### Skenario 2: Tambah Anggota Manual (tanpa dari diklat)
```
Admin membuka: /admin/members/create
    ↓
Admin bisa mengisi data kesehatan secara manual (opsional)
    ↓
Atau biarkan kosong jika tidak ada data
    ↓
Member tetap bisa dibuat tanpa data kesehatan
```

---

## Perubahan di Form Create

File: `resources/views/admin/members/create.blade.php`

Info box diperbaharui dengan catatan:
- "Data kesehatan (riwayat penyakit, alergi, no tlp ortu) akan diisi otomatis dari diklat registration jika ada"

Ini menginformasikan admin bahwa:
- Jika menambah member dari diklat yang disetujui → data kesehatan otomatis terisi
- Jika menambah member manual → data kesehatan bersifat opsional

---

## Controller Logic (Tetap Sama)

File: `app/Http/Controllers/Admin/DiklatRegistrationController.php`

Ketika status diubah menjadi "approved", system memanggil:

```php
if ($oldStatus !== 'approved' && $newStatus === 'approved') {
    if (!Member::where('npm', $registration->npm)->exists()) {
        Member::createFromDiklatRegistration($registration);  // ← Mengambil data kesehatan
    }
}
```

---

## Testing Checklist

- [ ] Buat pendaftaran diklat baru dengan data penyakit, alergi, dan no telp ortu
- [ ] Setujui pendaftaran (ubah status → "Diterima")
- [ ] Verifikasi bahwa member dibuat dengan data kesehatan terisi
- [ ] Buka halaman detail member dan pastikan data kesehatan ditampilkan
- [ ] Test tambah anggota manual - pastikan field kesehatan bersifat opsional

---

## Catatan Teknis

1. **Data null diizinkan** - Jika pendaftar diklat tidak mengisi data kesehatan, field member juga akan kosong (null)
2. **Tidak ada duplikasi** - Data diambil langsung saat konversi dari diklat registration
3. **Tetap bisa diedit** - Admin dapat mengedit data kesehatan di halaman edit member
4. **Backward compatible** - Member yang sudah ada tidak terpengaruh
