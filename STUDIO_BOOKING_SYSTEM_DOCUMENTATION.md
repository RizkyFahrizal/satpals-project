# Dokumentasi Sistem Fitur Booking Studio

## Overview
Sistem booking studio adalah fitur yang memungkinkan pengguna (public) untuk memesan studio UKM secara online, dan admin/pengurus dapat mengelola booking dengan efisien.

---

## 1. Fitur Booking Studio (Public Side)

### Flow Umum
1. **Browse & Search** - User melihat katalog studio, bisa filter berdasarkan tipe/harga/kapasitas
2. **Detail Studio** - User lihat foto, deskripsi, dan harga per jam
3. **Form Booking** - Pilih tanggal dan jam booking
4. **Availability Check** - Sistem cek jadwal (conflict detection)
5. **Checkout** - Isi data penyewa dan pilih metode pembayaran
6. **Payment** - Proses pembayaran
7. **Confirmation** - Terima booking code dan email confirmation

### Halaman-Halaman Utama

#### A. Equipment Index (Katalog)
```
GET /studio/equipment
- Tampilkan daftar studio dalam grid/list
- Fitur search by nama
- Filter by kategori (tipe studio)
- Sort by harga / rating
- Pagination
```

**View Components:**
- Studio card: foto, nama, tipe, harga/jam, rating
- Filter sidebar: kategori, harga range, kapasitas
- Search bar: cari studio by nama

#### B. Equipment Detail
```
GET /studio/equipment/{id}
- Foto studio (gallery)
- Deskripsi lengkap
- Spesifikasi: kapasitas, peralatan, harga/jam
- Rating & review dari booking sebelumnya
- Tombol "Book Now"
```

#### C. Booking Form
```
GET /studio/checkout/equipment/{id}
POST /studio/checkout/store
```

**Form Fields:**
```
- Tanggal booking (date picker)
- Jam mulai (time picker)
- Jam selesai (time picker) atau durasi
- Tujuan penggunaan (optional)
- Total harga (calculated real-time)
```

**Validation:**
- Tanggal minimal hari ini + 1 hari
- Jam mulai harus sebelum jam selesai
- Durasi minimal 1 jam, maksimal 8 jam
- Check availability pada tanggal/jam yang dipilih
- Cegah double booking

#### D. Checkout Page
```
GET /studio/checkout
POST /studio/checkout/process-payment
```

**Data Penyewa:**
```
- Nama lengkap (required)
- Email (required)
- Nomor telepon (required)
- Alamat (required)
```

**Metode Pembayaran:**
- Transfer Bank (Mandiri, BCA, BRI, etc)
- E-wallet (GCash, OVO, Dana, etc)
- Cash (optional, untuk walk-in atau cicilan)

**Order Summary:**
- Studio name + foto
- Tanggal & jam
- Durasi
- Harga per jam x durasi
- Biaya admin (jika ada)
- Total harga
- Booking code (auto-generated)

#### E. My Bookings
```
GET /studio/bookings
```

**Fitur:**
- Lihat daftar booking user (upcoming + past)
- Status badge: Pending, Confirmed, In Progress, Completed, Cancelled
- Filter by status
- Aksi: View Detail, Cancel (jika belum confirmed), Download Receipt

**Status Flow:**
```
Pending → Confirmed → In Progress → Completed
    ↓         ↓
  Cancelled  Cancelled
```

### Database Schema

#### bookings table
```sql
CREATE TABLE bookings (
    id BIGINT PRIMARY KEY,
    equipment_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    booking_code VARCHAR (50) UNIQUE,
    nama_penyewa VARCHAR (255),
    email VARCHAR (255),
    telepon VARCHAR (20),
    alamat TEXT,
    tanggal_booking DATE,
    jam_mulai TIME,
    jam_selesai TIME,
    durasi_jam INT,
    harga_per_jam DECIMAL (10,2),
    total_harga DECIMAL (10,2),
    biaya_admin DECIMAL (10,2),
    metode_pembayaran VARCHAR (50),
    status ENUM ('pending', 'confirmed', 'in_progress', 'completed', 'cancelled'),
    payment_status ENUM ('unpaid', 'pending', 'paid', 'failed'),
    checkout_time DATETIME NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Controller Methods (Public)

```php
// EquipmentPublicController
public function index()
public function show($id)
public function search(Request $request)

// CheckoutController
public function index($equipmentId)
public function checkAvailability(Request $request)
public function store(StoreBookingRequest $request)
public function processPayment(Request $request)

// BookingPublicController
public function myBookings()
public function detail($bookingCode)
public function cancel($bookingCode)
public function receipt($bookingCode)
```

---

## 2. Management Booking Studio (Admin/Pengurus)

### Flow Umum
1. **Dashboard** - View statistik & calendar
2. **Search & Filter** - Cari booking by code/nama/status
3. **Approve/Reject** - Terima atau tolak booking pending
4. **Check In/Out** - Tracking penggunaan actual
5. **Reports** - Analytics dan laporan

### Halaman-Halaman Utama

#### A. Booking Dashboard
```
GET /admin/bookings/dashboard
```

**Metrics:**
- Total booking hari ini
- Revenue hari ini
- Studio occupancy rate (berapa % studio terpakai)
- Pending bookings (belum approved)

**Calendar View:**
- Lihat jadwal per studio
- Color-coded: pending (yellow), confirmed (green), in-progress (blue), etc
- Click event untuk lihat detail

#### B. Booking Management
```
GET /admin/bookings
```

**Tabel:**
```
| Booking Code | Studio | Penyewa | Tanggal | Jam | Status | Action |
|---|---|---|---|---|---|---|
| BK-001 | Studio A | Azis | 2026-04-20 | 10:00-12:00 | Pending | Approve/Reject |
| BK-002 | Studio B | Reza | 2026-04-21 | 14:00-16:00 | Confirmed | Check In |
```

**Filter:**
- By status (pending, confirmed, in-progress, completed, cancelled)
- By studio
- By date range
- By payment status

**Aksi:**
- View Detail
- Approve (confirm booking)
- Reject (cancel booking)
- Check In (update to in-progress)
- Check Out (update to completed)
- Generate Receipt / Invoice

#### C. Booking Detail & Management
```
GET /admin/bookings/{id}
POST /admin/bookings/{id}/approve
POST /admin/bookings/{id}/reject
POST /admin/bookings/{id}/check-in
POST /admin/bookings/{id}/check-out
```

**Display:**
- Booking info (code, tanggal, jam, durasi)
- Customer info (nama, email, telepon)
- Payment info (metode, status, amount)
- Studio info (nama, deskripsi, harga)
- Waktu check in/out (actual)
- Extra charge (jika ada)

**Aksi:**
- **Approve**: Set status = confirmed, kirim notifikasi customer
- **Reject**: Set status = cancelled, input alasan, process refund
- **Check In**: Verifikasi penyewa hadir, update status = in-progress
- **Check Out**: Catat waktu checkout actual, calculate extra charge jika overtime

#### D. Reports & Analytics
```
GET /admin/bookings/reports
```

**Report Types:**
1. **Booking per Studio** - Berapa booking untuk setiap studio
2. **Revenue Report** - Total revenue per periode
3. **Peak Hours** - Jam-jam dengan booking terbanyak
4. **Cancellation Rate** - Berapa % booking yang dibatalkan
5. **Customer Analytics** - Top customers, repeat bookings

**Export:**
- Download as PDF
- Download as Excel
- Schedule automated email reports

### Database Schema (Additional)

```sql
-- Tambahan field di bookings table
ALTER TABLE bookings ADD COLUMN (
    jam_checkin_actual TIME NULL,
    jam_checkout_actual TIME NULL,
    durasi_actual_jam INT NULL,
    extra_charge DECIMAL(10,2) DEFAULT 0,
    catatan_admin TEXT NULL,
    approved_by BIGINT,
    approved_at TIMESTAMP NULL,
    rejected_reason TEXT NULL
);

CREATE TABLE booking_logs (
    id BIGINT PRIMARY KEY,
    booking_id BIGINT,
    action VARCHAR (50),
    detail TEXT,
    changed_by BIGINT,
    created_at TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
);
```

### Controller Methods (Admin)

```php
// BookingController (Admin)
public function dashboard()
public function index()
public function show($id)
public function approve($id)
public function reject($id)
public function checkin($id)
public function checkout($id)
public function generateReceipt($id)
public function reports()
public function exportReport($type)
```

---

## 3. Key Features

### A. Availability Check
```
Sistem otomatis cek:
- Tanggal booking tidak konflikt dengan booking existing
- Jam mulai < jam selesai
- Durasi dalam range yang allowed
- Studio dalam status "available"
```

### B. Automatic Code Generation
```
Booking code format: BK-{YYMMDD}-{randomcode}
Contoh: BK-260420-A7K2M9
```

### C. Payment Integration
```
- Support multiple payment methods
- Automatic payment verification
- Automatic receipt generation
- Email confirmation to customer
```

### D. Notification System
```
Email notification:
- Booking confirmation (setelah payment sukses)
- Booking approved (oleh admin)
- Booking rejection (beserta alasan)
- Reminder (H-1 sebelum booking)
- Receipt/Invoice (setelah checkout)
```

### E. Extra Charge System
```
Jika user melebihi waktu yang dibooking:
- Detected saat checkout actual
- Calculate extra hour(s)
- Apply extra rate (bisa 1.5x atau 2x normal rate)
- Add ke final invoice
```

---

## 4. Authorization & Permissions

### Public User
- View studio catalog
- Search & filter
- Create booking
- View own bookings
- Cancel own booking (sebelum confirmed)

### Admin/Pengurus
- View all bookings
- Approve/reject booking
- Check in/out
- View reports
- Export reports
- Manage studio (enable/disable)

### Guest
- View studio catalog
- Can't book (redirect to login)

---

## 5. Keuntungan Sistem Booking Studio

| Aspek | Manfaat |
|-------|---------|
| **Automation** | Eliminate manual booking request handling |
| **Availability** | 24/7 booking akses untuk public |
| **Accuracy** | Auto conflict detection mencegah double booking |
| **Revenue** | Clear tracking of income per studio |
| **Analytics** | Understand peak hours & customer behavior |
| **Efficiency** | Admin time saved, fokus pada operational |
| **Transparency** | Clear status & communication dengan customer |
| **Auditability** | All booking history & logs tercatat |

---

## 6. Flow Summary

### Public User Journey
```
Browse Studio → View Detail → Book Now → Isi Form → 
Checkout → Payment → Confirmation Email → My Bookings
```

### Admin Management Journey
```
Dashboard → Search Booking → Review → Approve/Reject → 
Check In → Check Out → Generate Receipt → View Reports
```

---

## Kesimpulan

Sistem booking studio ini memberikan:
- **Convenience** untuk public (easy booking online)
- **Efficiency** untuk admin (automated workflow)
- **Traceability** untuk semua pihak (clear audit trail)
- **Revenue Control** (payment tracking & reporting)
- **Customer Satisfaction** (confirmation & reminder notifications)
