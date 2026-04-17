# Dokumentasi Sistem Equipment Rental

## Overview
Sistem equipment rental adalah fitur untuk menyewakan peralatan UKM (drum, gitar, keyboard, microphone, speaker, cable, dll) kepada publik dengan sistem booking online, approval workflow, dan tracking pengembalian.

---

## 1. Equipment Rental (Public Side)

### Flow Umum
1. **Browse Catalog** - User lihat katalog equipment tersedia
2. **Search & Filter** - Filter by kategori, harga, kondisi
3. **View Detail** - Lihat foto, spec, harga per hari
4. **Booking Form** - Pilih tanggal dan durasi rental
5. **Availability Check** - Sistem cek ketersediaan
6. **Checkout** - Isi data penyewa dan metode pembayaran
7. **Payment** - Proses pembayaran
8. **Confirmation** - Terima rental code dan email

### Halaman Utama

#### A. Equipment Catalog
```
GET /equipment/rental
- List equipment dengan foto, nama, kategori, harga/hari
- Filter: kategori, harga range, kondisi
- Search by nama
- Pagination
```

**Display per Item:**
- Foto equipment
- Nama
- Kategori (drum, gitar, keyboard, mic, speaker, cable, dll)
- Kondisi (good/excellent/fair)
- Harga per hari
- Rating
- "Rent Now" button

#### B. Equipment Detail
```
GET /equipment/rental/{id}
```

**Display:**
- Photo gallery
- Deskripsi lengkap
- Spesifikasi teknis
- Kondisi
- Harga per hari
- Ketersediaan status
- Rating & review
- "Rent Now" button

#### C. Booking Form
```
POST /equipment/rental/book
```

**Form Fields:**
```
Tanggal Mulai (date picker) - required
Durasi Rental (days) - required (1-30)
Tujuan Penggunaan (optional)

Auto-calculated:
Total Harga = Durasi × Harga per hari
```

**Validation:**
- Tanggal >= hari ini
- Durasi 1-30 hari
- Check availability (no conflict)

#### D. Checkout
```
GET /equipment/checkout
POST /equipment/checkout/process
```

**Data Penyewa:**
```
Nama Lengkap
Email
Telepon
Alamat
KTP/ID (optional)
```

**Payment Methods:**
- Transfer Bank
- E-wallet
- Cash on pickup

**Summary:**
```
Equipment: Yamaha Keyboard
Tanggal: 2026-04-20 - 2026-04-23
Durasi: 3 hari
Harga: 3 × Rp 200,000 = Rp 600,000
Admin Fee: Rp 30,000
Total: Rp 630,000
Rental Code: ER-260420-X5Y8Z2
```

#### E. My Rentals
```
GET /equipment/my-rentals
```

**Display:**
- List of user's rentals (upcoming + past)
- Status: Pending, Confirmed, In Use, Returned, Cancelled
- Filter by status
- Actions: View Detail, Cancel (if pending), Download Receipt

**Status Flow:**
```
Pending → Confirmed → In Use → Returned
   ↓        ↓
Cancelled  Cancelled
```

### Database Schema

#### equipment_items table
```sql
CREATE TABLE equipment_items (
    id BIGINT PRIMARY KEY,
    nama_alat VARCHAR(255) NOT NULL,
    kategori VARCHAR(50), -- drum, gitar, keyboard, mic, speaker, cable, dll
    deskripsi TEXT,
    kondisi ENUM('fair', 'good', 'excellent'),
    foto VARCHAR(255),
    harga_per_hari DECIMAL(10,2),
    status ENUM('available', 'rented', 'maintenance'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### equipment_rentals table
```sql
CREATE TABLE equipment_rentals (
    id BIGINT PRIMARY KEY,
    equipment_id BIGINT NOT NULL,
    user_id BIGINT,
    rental_code VARCHAR(50) UNIQUE,
    nama_penyewa VARCHAR(255),
    email VARCHAR(255),
    telepon VARCHAR(20),
    alamat TEXT,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    durasi_hari INT,
    harga_per_hari DECIMAL(10,2),
    total_harga DECIMAL(10,2),
    biaya_tambahan DECIMAL(10,2),
    metode_pembayaran VARCHAR(50),
    status ENUM('pending', 'confirmed', 'in_use', 'returned', 'cancelled'),
    payment_status ENUM('unpaid', 'pending', 'paid', 'failed'),
    tujuan_penggunaan VARCHAR(255),
    pickup_at TIMESTAMP,
    return_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (equipment_id) REFERENCES equipment_items(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Controller Methods (Public)

```php
// EquipmentRentalPublicController
public function index() // Catalog
public function show($id) // Detail
public function search(Request $request) // Search

// EquipmentCheckoutController
public function index($equipmentId) // Booking form
public function checkAvailability(Request $request)
public function store(Request $request)
public function processPayment(Request $request)

// MyEquipmentRentalsController
public function index() // My rentals
public function detail($rentalCode)
public function cancel($rentalCode)
public function receipt($rentalCode)
```

---

## 2. Equipment Rental Management (Pengurus)

### Flow Umum
1. **Dashboard** - View statistik & calendar
2. **Search & Filter** - Cari rental by code/nama
3. **Approve/Reject** - Terima atau tolak rental pending
4. **Start/End Rental** - Track pickup dan pengembalian
5. **Reports** - View analytics

### Halaman Utama

#### A. Rental Dashboard
```
GET /admin/equipment-rentals/dashboard
```

**Metrics:**
- Total rental hari ini
- Revenue hari ini
- Equipment utilization %
- Pending rental count

**Calendar:** Jadwal rental per equipment

#### B. Rental Management
```
GET /admin/equipment-rentals
```

**Table:**
```
| Rental Code | Equipment | Penyewa | Tanggal | Durasi | Status |
|---|---|---|---|---|---|
| ER-001 | Yamaha Keyboard | Azis | 2026-04-20 | 3h | Pending |
| ER-002 | Gitar Fender | Reza | 2026-04-21 | 2h | Confirmed |
```

**Filter:**
- Status, equipment, date range, payment status

#### C. Rental Detail & Action
```
GET /admin/equipment-rentals/{id}
POST /admin/equipment-rentals/{id}/approve
POST /admin/equipment-rentals/{id}/start
POST /admin/equipment-rentals/{id}/end
```

**Actions:**
- **Approve** - Confirm pending rental
- **Start Rental** - Record pickup
- **End Rental** - Record return + condition + extra fees
- **Cancel** - Cancel rental
- **Invoice** - Download invoice

#### D. Reports
```
GET /admin/equipment-rentals/reports
```

**Report Types:**
1. **Rental per Item** - Which items rented most
2. **Revenue Report** - Income per period
3. **Utilization Rate** - % equipment in use
4. **Peak Dates** - Most rented dates
5. **Customer Analytics** - Top customers

---

## 3. Key Features

### A. Availability Check
```
Sistem otomatis cek:
- Equipment tidak dalam rental period yang sama
- No double booking
- Check status = available
```

### B. Automatic Code Generation
```
Format: ER-{YYMMDD}-{randomcode}
Contoh: ER-260420-X5Y8Z2
```

### C. Extra Charges
```
Late Fee: Rp 50,000 per hari (configurable)
Damage Fee: Input manual saat end rental
```

### D. Notifications
```
- Rental confirmation email
- Pickup reminder (H0)
- Return reminder (H0)
- Late notification
- Return confirmation
```

### E. Rating & Review
```
Setelah rental selesai:
- Customer rate equipment (1-5 stars)
- Customer buat review
- Display di detail equipment
```

---

## 4. Authorization

### Public
- View catalog
- Create rental
- View own rentals
- Rate & review (after rental)

### Pengurus
- Manage all rentals
- Approve/Reject
- Start/End rental
- View reports
- Manage equipment

---

## 5. Use Cases

### Use Case 1: Normal Rental
```
1. Azis browse katalog → pilih Yamaha Keyboard
2. Klik "Rent Now"
3. Pilih 2026-04-20 s/d 2026-04-23 (3 hari)
4. Harga: 3 × Rp 200K = Rp 600K
5. Checkout & bayar
6. Dapat rental code ER-260420-X5Y8Z2
7. Email confirmation
8. Pengurus approve rental
9. Azis pickup sesuai jadwal
10. Pengurus record pickup condition
11. Azis return equipment
12. Pengurus record return condition
13. Rental complete
14. Azis dapat email "Return Confirmed"
15. Azis rate equipment
```

### Use Case 2: Late Return
```
1. Rental scheduled return: 2026-04-22
2. Actual return: 2026-04-23 (1 hari late)
3. Pengurus add late charge: 1 × Rp 50K
4. Final invoice: Rp 600K + Rp 50K = Rp 650K
5. Customer pay extra charge
```

---

## 6. Keuntungan Sistem

| Aspek | Manfaat |
|-------|---------|
| **Online Booking** | 24/7 akses |
| **Availability Check** | Prevent double booking |
| **Revenue Tracking** | Clear income tracking |
| **Condition Tracking** | Document kondisi alat |
| **Automated Notifications** | Reduce manual communication |
| **Rating System** | Get feedback |
| **Late Fee Support** | Extra income |
| **Analytics** | Understand demand |

---

## 7. Workflow Summary

### Public User
```
Browse → Detail → Book → Checkout → Payment → 
Confirmation → My Rentals → Pickup → Return → 
Rate & Review
```

### Pengurus
```
Dashboard → Search → Review → Approve/Reject → 
Start Rental → End Rental → Generate Invoice → Reports
```

---

## Kesimpulan

Sistem Equipment Rental memberikan:
- **Convenience** untuk publik
- **Control** untuk pengurus
- **Revenue optimization** (late fees, analytics)
- **Transparency** (confirmation, tracking, invoicing)
- **Quality** (rating system, condition tracking)
- **Automation** (availability check, notifications, code generation)
