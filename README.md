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

**SATPALS** adalah aplikasi web manajemen organisasi yang dirancang khusus untuk kelompok kesenian **Satya Palapa** di UPN Veteran Jawa Timur. Aplikasi ini membantu mengelola berbagai aspek organisasi dengan antarmuka yang user-friendly dan fitur-fitur yang komprehensif.

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

#### 📚 **Kelola Diklat (Training)**
- Daftar peserta diklat (public form)
- Kelola data peserta (admin panel)
- Filter peserta berdasarkan periode
- Export data peserta

#### 🏆 **Prestasi & Kegiatan**
- Dokumentasi prestasi organisasi
- Kelola kegiatan/event
- Timeline aktivitas
- Galeri foto kegiatan

#### 💰 **Manajemen Keuangan** *(implemented)*
- Pencatatan pemasukan/pengeluaran
- Kategorisasi transaksi
- Dashboard keuangan dengan statistik
- Filter berdasarkan jenis & periode
- Tracking creator untuk setiap transaksi

#### 🎷 **Persewaan Band** *(implemented)*
- Kelola paket band dengan harga per jam dan per event
- Form permintaan sewa band (public)
- Approval & rejection system untuk admin
- Invoice PDF otomatis saat approval
- Email notification ke penyewa
- Pembatalan rental dengan income rejection
- Tracking nomor pesanan & status sewa

#### 🎬 **Booking Studio** *(coming soon)*
- Reservasi studio recording
- Manajemen jadwal slot

---

## � Alur Proses Utama

### Band Rental Workflow
```
Public User: Membuat Request → Admin: Review & Approve/Reject 
→ Invoice Generate → Email Invoice → Payment → Rental Active → Can Cancel
```

### Financial Management
- Pemasukan dari band rental, events, dll masuk ke dashboard
- Admin tracking creator setiap transaksi
- Report keuangan per periode

### Document Management
- Upload template → kategori & preview → user download
- Arsip surat masuk/keluar dengan tracking nomor

---

## �🚀 Quick Start

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

## ✨ Latest Improvements (April 2026)

### Band Rental System
- ✅ Venue address tracking untuk detail lokasi pertunjukan
- ✅ Auto-calculate performance duration (total jam - break jam)
- ✅ Email invoice otomatis saat approval dengan detail lengkap
- ✅ Pembatalan rental dengan income status rejection (bukan delete)
- ✅ Admin & public UI improvements (back buttons, member count display)

### Financial Dashboard
- ✅ Separated pending counts (income & expense)
- ✅ Creator name attribution untuk audit trail
- ✅ Status tracking untuk setiap transaksi

### UI/UX Enhancements
- ✅ Consistent color scheme untuk status tabs
- ✅ Social media icons display fix (WhatsApp, Instagram, TikTok, YouTube)
- ✅ Better navigation with back buttons

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

## 🤝 Contributing

Kontribusi sangat diterima! Untuk berkontribusi:

1. Fork repository ini
2. Buat branch fitur: `git checkout -b feature/AmazingFeature`
3. Commit changes: `git commit -m 'Add AmazingFeature'`
4. Push ke branch: `git push origin feature/AmazingFeature`
5. Buka Pull Request

---

## 📄 License

Project ini dilisensikan di bawah lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

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

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
