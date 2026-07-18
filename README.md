# 🎸 SATPALS - Satya Palapa Organization Management System

<p align="center">
  <strong>Sistem Manajemen Organisasi UKM Satya Palapa</strong><br>
  <em>Kelompok Kesenian UPN Veteran Jawa Timur</em>
</p>

<p align="center">
  <a href="https://github.com/RizkyFahrizal/satpals-project"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP"></a>
  <a href="#"><img src="https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS"></a>
  <a href="#"><img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql" alt="MySQL"></a>
</p>

---

## 📖 Tentang SATPALS

**SATPALS** adalah aplikasi web manajemen organisasi untuk **Satya Palapa** di UPN Veteran Jawa Timur. Aplikasi ini dipakai untuk mengelola struktur pengurus, arsip dokumen, keuangan, booking studio, persewaan band, persewaan alat, dan pendaftaran diklat dalam satu sistem.

### ✨ Fitur Utama

#### 👥 **Manajemen Struktur Pengurus**
- Kelola struktur organisasi (BPH & Subsie)
- Kelola periode organisasi
- Tampilkan anggota dengan informasi lengkap (foto, nama, jabatan, prodi)

#### 📄 **Kelola Template Surat**
- Upload template surat (PDF, Word, Excel)
- Kategori template (Surat, RAB, Proposal, LPJ, Lainnya)
- Preview dan download template
- Pencarian & filter berdasarkan kategori

#### 📮 **Arsip Surat Masuk/Keluar**
- Catat surat masuk dan keluar
- Filter berdasarkan jenis, tahun, dan bulan
- Tracking nomor surat, pengirim, penerima, perihal
- Preview dan download dokumen
- Statistik surat masuk/keluar

#### 📚 **Kelola Diklat**
- Pendaftaran diklat via form public
- Pengelolaan peserta diklat di admin panel
- Status dan data peserta terpusat
- Siap dipakai untuk rekap/monitoring pendaftaran

#### 🏆 **Prestasi & Kegiatan**
- Dokumentasi prestasi organisasi
- Kelola kegiatan/event
- Timeline aktivitas
- Galeri foto kegiatan

#### 💰 **Manajemen Keuangan** *(implemented)*
- Pencatatan pemasukan dan pengeluaran
- Dashboard ringkas dengan total, status, dan transaksi terbaru
- Filter berdasarkan periode, tipe, status, jenis pengeluaran, judul, keterangan, dan dibuat oleh
- Tracking creator untuk audit trail

#### 🎷 **Persewaan Band** *(implemented)*
- Kelola data band dan harga sewa
- Form permintaan sewa band untuk public
- Approval, rejection, cancel, dan complete oleh admin
- Invoice PDF otomatis saat approval
- Email notifikasi ke penyewa
- Status pesanan dan income terhubung

#### 🎬 **Booking Studio** *(implemented)*
- Booking studio untuk public
- Approval, rejection, cancel, dan complete oleh admin
- Invoice PDF saat booking disetujui
- Email notifikasi dan ringkasan pembayaran

#### 🧰 **Persewaan Alat** *(implemented)*
- Keranjang public untuk memilih alat
- Checkout dengan data penyewa dan periode sewa
- Approval, rejection, cancel, dan complete oleh admin
- Invoice PDF dan email otomatis
- Integrasi ke keuangan saat disetujui

---

## 🧭 Alur Proses Singkat

### Booking Studio
Public isi form booking → admin review → approve/reject → invoice PDF dikirim → user menerima notifikasi email.

### Sewa Band
Public buat permintaan sewa band → admin cek jadwal dan harga → approve/reject → invoice dibuat → pembayaran dikonfirmasi → status bisa selesai/dibatalkan.

### Sewa Alat
User pilih alat ke keranjang → checkout isi data & tanggal sewa → admin approve/reject → invoice dikirim → jika dibatalkan, income ikut ditandai rejected.

### Pendaftaran Diklat
Calon peserta isi form pendaftaran → data masuk ke admin panel → admin kelola status dan rekap peserta per periode.

### Keuangan
Transaksi pemasukan/pengeluaran dicatat di dashboard → admin bisa filter, cari, dan memantau status transaksi.

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL 8.0 atau lebih tinggi
- Git

### 1️⃣ Clone Repository

```bash
git clone https://github.com/RizkyFahrizal/satpals-project.git
cd satpals-project
```

### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3️⃣ Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Compile assets
npm run build
```

### 4️⃣ Database Setup

```bash
# Buat database MySQL bernama 'satpals'
# Update file .env dengan credential database kamu

# Run migrations
php artisan migrate

# (Optional) Seed data dummy
php artisan db:seed
```

### 5️⃣ Jalankan Aplikasi

```bash
# Start development server
php artisan serve

# Di terminal lain, compile assets (watch mode)
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## 🔐 Default Credentials (Setelah Seeding)

```
Super Admin:
Email: admin@satpals.com
Password: password

Pengurus:
Email: pengurus@satpals.com
Password: password
```

---

## ✨ Recent Improvements (April 2026)

- ✅ Booking studio, sewa band, dan sewa alat memakai alur approval/invoice/email yang lebih konsisten
- ✅ Persewaan alat terhubung ke keuangan saat disetujui
- ✅ Modal konfirmasi untuk approve, reject, cancel, dan complete diperjelas
- ✅ Halaman checkout sewa alat kini punya success page khusus
- ✅ Dashboard keuangan punya filter pencarian berdasarkan judul, keterangan, dan pembuat transaksi
- ✅ Template email dan invoice diperbarui agar lebih jelas di inbox dan PDF

---

## 🎨 Tech Stack

| Technology | Version | Purpose |
|-----------|---------|---------|
| **Laravel** | 11.x | Backend Framework |
| **PHP** | 8.2+ | Programming Language |
| **MySQL** | 8.0+ | Database |
| **Tailwind CSS** | 3.x | Styling |
| **DaisyUI** | 4.7+ | UI Components |
| **Blade** | - | Templating Engine |
| **Eloquent** | - | ORM |

---

## 📝 Development Guidelines

### Adding Features

1. Create migration: `php artisan make:model Feature -m`
2. Define model in `app/Models/`
3. Create controller: `php artisan make:controller Admin/FeatureController`
4. Add routes in `routes/web.php`
5. Create views in `resources/views/admin/features/`
6. Run migrations: `php artisan migrate`

### Running Tests

```bash
php artisan test
```

### Code Style

Project menggunakan Laravel best practices:
- PSR-12 for PHP code style
- Blade templating conventions
- RESTful routing principles

---

## 📚 Documentation

- **Activity Diagrams**: `/docs/activity-diagrams/` - Deskripsi alur proses setiap fitur
- **Sequence Diagrams**: `/docs/sequence-diagrams/` - Interaksi antar komponen sistem

---

## 👨‍💻 Developer

**Rizky Fahrizal**
- GitHub: [@RizkyFahrizal](https://github.com/RizkyFahrizal)
- Project: Skripsi S1 Sistem Informasi
- Institusi: UPN Veteran Jawa Timur

---

## 💡 Support

Jika ada pertanyaan atau issue, silakan buat [GitHub Issue](https://github.com/RizkyFahrizal/satpals-project/issues) atau hubungi developer.

---

<p align="center">
  Made with ❤️ for Satya Palapa Organization
</p>

## 📄 License

Project ini dilisensikan di bawah lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.
