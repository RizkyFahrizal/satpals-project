# Dokumentasi Sistem Band Rental

## Overview
Sistem band rental adalah fitur untuk menyewakan peralatan band kepada publik secara online dengan approval workflow dan tracking yang terstruktur.

---

## 1. Band Rental (Public Side)

### Flow Umum
1. **Browse & Search** - User lihat katalog band, bisa filter by tipe/harga/rating
2. **Detail Band** - User lihat foto, spek, harga per hari
3. **Form Rental** - Pilih tanggal dan durasi rental
4. **Availability Check** - Sistem cek availability (conflict detection)
5. **Checkout** - Isi data penyewa dan metode pembayaran
6. **Payment** - Proses pembayaran
7. **Confirmation** - Terima rental code dan email confirmation

### Halaman-Halaman Utama

#### A. Band Catalog
```
GET /bands/rental
- Tampilkan daftar band dalam grid/list
- Filter: tipe (drum, gitar, keyboard, dll), harga range, rating
- Search by nama item
- Pagination
```

**Display:**
- Band foto/thumbnail
- Nama item
- Tipe
- Kondisi (good/excellent/fair)
- Harga per hari
- Rating & review count
- "Rent Now" button

#### B. Band Detail
```
GET /bands/rental/{id}
```

**Display:**
- Foto gallery (multiple photos)
- Deskripsi lengkap
- Spesifikasi: tipe, kondisi, keadaan
- Harga per hari
- Tersedia (yes/no)
- Rating & review dari rental sebelumnya
- "Rent Now" button

#### C. Rental Form
```
GET /bands/rental/{id}/checkout
POST /bands/rental/store
```

**Form Fields:**
```
Tanggal Mulai (date picker) - required
Durasi (in days) - required (min 1, max 30)
Total Price (calculated real-time)

Tujuan Penggunaan (optional)
Contoh: "Konser", "Practice", "Event"
```

**Validation:**
- Tanggal minimal hari ini
- Durasi 1-30 hari
- Check availability
- Prevent double rental pada tanggal/item yang sama

#### D. Checkout Page
```
GET /bands/checkout
POST /bands/checkout/process-payment
```

**Data Penyewa:**
```
Nama Lengkap (required)
Email (required)
Telepon (required)
Alamat (required)
KTP/ID Number (optional)
```

**Payment Methods:**
- Transfer Bank
- E-wallet
- Cash (on pickup)

**Order Summary:**
```
Band Item: Yamaha Drum Set
Tanggal: 2026-04-20 s/d 2026-04-23
Durasi: 3 hari
Harga per hari: Rp 300,000
Subtotal: Rp 900,000
Admin fee: Rp 50,000
Total: Rp 950,000
Rental Code: BR-260420-K9L2M5
```

#### E. My Rentals
```
GET /bands/my-rentals
```

**Display:**
- Daftar rental user (upcoming + past)
- Status badge: Pending, Confirmed, In Use, Returned
- Filter by status
- Actions: View Detail, Cancel (if pending), Download Receipt

**Status Flow:**
```
Pending → Confirmed → In Use → Returned
   ↓         ↓
Cancelled  Cancelled
```

### Database Schema

#### band_items table
```sql
CREATE TABLE band_items (
    id BIGINT PRIMARY KEY,
    nama_item VARCHAR(255) NOT NULL,
    tipe VARCHAR(50), -- drum, gitar, keyboard, bass, mic, cable, dll
    deskripsi TEXT,
    kondisi ENUM('fair', 'good', 'excellent'),
    foto VARCHAR(255),
    harga_per_hari DECIMAL(10,2),
    status ENUM('available', 'rented', 'maintenance'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### band_rentals table
```sql
CREATE TABLE band_rentals (
    id BIGINT PRIMARY KEY,
    band_item_id BIGINT NOT NULL,
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
    biaya_tambahan DECIMAL(10,2), -- damage, late fee
    metode_pembayaran VARCHAR(50),
    status ENUM('pending', 'confirmed', 'in_use', 'returned', 'cancelled'),
    payment_status ENUM('unpaid', 'pending', 'paid', 'failed'),
    tujuan_penggunaan VARCHAR(255),
    catatan TEXT,
    created_at TIMESTAMP,
    pickup_at TIMESTAMP,
    return_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (band_item_id) REFERENCES band_items(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Controller Methods (Public)

```php
// BandRentalPublicController
public function index() // Lihat katalog band
public function show($id) // Detail band
public function search(Request $request) // Search & filter

// RentalCheckoutController
public function index($bandId) // Form rental
public function checkAvailability(Request $request) // AJAX
public function store(StoreRentalRequest $request)
public function processPayment(Request $request)

// MyRentalsController
public function index() // My rentals list
public function detail($rentalCode)
public function cancel($rentalCode)
public function receipt($rentalCode)
```

---

## 2. Band Rental Management (Pengurus Side)

### Flow Umum
1. **Dashboard** - View statistik & calendar
2. **Search & Filter** - Cari rental by code/nama/status
3. **Approve/Reject** - Terima atau tolak rental pending
4. **Start/End Rental** - Track pengambilan & pengembalian
5. **Reports** - Analytics dan laporan

### Halaman-Halaman Utama

#### A. Rental Dashboard
```
GET /admin/band-rentals/dashboard
```

**Metrics:**
- Total rental hari ini
- Revenue hari ini
- Band utilization rate (berapa % band terpakai)
- Pending rental (belum diapprove)

**Calendar View:**
- Lihat jadwal per band
- Color-coded: pending, confirmed, in-use, returned
- Click event untuk detail

#### B. Rental Management
```
GET /admin/band-rentals
```

**Table:**
```
| Rental Code | Band Item | Penyewa | Tanggal | Durasi | Status | Action |
|---|---|---|---|---|---|---|
| BR-001 | Yamaha Drum | Azis | 2026-04-20 | 3h | Pending | Approve/Reject |
| BR-002 | Gitar Fender | Reza | 2026-04-21 | 2h | Confirmed | Start/End |
```

**Filter:**
- By status (pending, confirmed, in-use, returned, cancelled)
- By band item
- By date range
- By payment status

#### C. Rental Detail & Action
```
GET /admin/band-rentals/{id}
POST /admin/band-rentals/{id}/approve
POST /admin/band-rentals/{id}/start
POST /admin/band-rentals/{id}/end
```

**Display:**
- Rental info: code, item, tanggal, durasi, harga
- Customer info: nama, telepon, alamat, ID
- Payment status
- Current status + timestamp

**Actions Available:**
- **Approve** (pending rental) - Confirm & send notif to customer
- **Start Rental** - Verify customer pickup, record start time
- **End Rental** - Check condition, record damage/late fees
- **Cancel** - Cancel rental (refund if applicable)
- **Download Invoice** - Generate & download

#### D. Reports & Analytics
```
GET /admin/band-rentals/reports
```

**Report Types:**
1. **Rental per Item** - Which items rented most
2. **Revenue Report** - Total income per period
3. **Utilization Rate** - % band utilization
4. **Peak Dates** - Most rented dates
5. **Customer Analytics** - Top customers, repeat rentals

**Charts:**
- Income trend (line chart)
- Item popularity (bar chart)
- Status distribution (pie chart)

### Database Schema (Management)

```sql
ALTER TABLE band_rentals ADD COLUMN (
    approved_by BIGINT,
    approved_at TIMESTAMP,
    pickup_verified BOOLEAN DEFAULT FALSE,
    kondisi_saat_pickup VARCHAR(255),
    kondisi_saat_return VARCHAR(255),
    kerusakan TEXT,
    late_charge DECIMAL(10,2) DEFAULT 0,
    damage_charge DECIMAL(10,2) DEFAULT 0
);

CREATE TABLE band_rental_logs (
    id BIGINT PRIMARY KEY,
    rental_id BIGINT,
    action VARCHAR(50), -- approve, start, end, cancel
    detail TEXT,
    changed_by BIGINT,
    created_at TIMESTAMP,
    FOREIGN KEY (rental_id) REFERENCES band_rentals(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
);
```

### Controller Methods (Pengurus)

```php
// BandRentalController (Admin)
public function dashboard()
public function index()
public function show($id)
public function approve($id)
public function reject($id)
public function startRental($id)
public function endRental($id, Request $request)
public function cancel($id)
public function reports()
public function exportReport($type)

// Code example - Dashboard
public function dashboard()
{
    $today = now()->toDateString();
    
    $totalRentalToday = BandRental::where('status', 'confirmed')
        ->whereDate('tanggal_mulai', $today)
        ->count();
    
    $revenueToday = BandRental::where('status', 'returned')
        ->whereDate('return_at', $today)
        ->sum('total_harga');
    
    $utilization = BandItem::where('status', 'rented')->count() / 
                   BandItem::count() * 100;
    
    return view('admin.band-rental.dashboard', [
        'totalRentalToday' => $totalRentalToday,
        'revenueToday' => $revenueToday,
        'utilization' => $utilization,
    ]);
}
```

---

## 3. Key Features

### A. Availability Check
```
Sistem otomatis cek:
- Band item tidak dalam rental period yang sama
- Tanggal checkout > checkout date sebelumnya
- Item status = available (not maintenance)
```

### B. Automatic Code Generation
```
Format: BR-{YYMMDD}-{randomcode}
Contoh: BR-260420-A7K2M9
```

### C. Late Fee & Damage Charge
```
Late Fee:
- Jika return > scheduled date
- Rate: Rp 50,000 per hari tambahan (configurable)

Damage Charge:
- Recorded saat end rental
- Manual input dari pengurus
- Added to final invoice
```

### D. Notification System
```
Email notification:
- Rental confirmation (setelah payment sukses)
- Rental reminder (H-1 sebelum rental mulai)
- Pickup reminder (H0 jam pickup)
- Return reminder (H0 hari return)
- Return received confirmation
```

### E. Rating & Review
```
Setelah rental selesai:
- Customer bisa rate item (1-5 stars)
- Customer bisa buat review
- Review ditampilkan di item detail
```

---

## 4. Authorization & Permissions

### Public User
- View band catalog
- Search & filter
- Create rental
- View own rentals
- Rate & review (setelah rental selesai)

### Pengurus (Admin)
- View all rentals
- Approve/Reject rental
- Start/End rental
- View reports
- Export reports
- Manage band items (enable/disable)
- Configure pricing

### Guest
- View catalog (can't rent)

---

## 5. Keuntungan Sistem Band Rental

| Aspek | Manfaat |
|-------|---------|
| **Online Booking** | 24/7 akses untuk rental request |
| **Availability Check** | Prevent double booking otomatis |
| **Revenue Tracking** | Clear income per item & period |
| **Condition Tracking** | Document kondisi saat pickup & return |
| **Automated Notification** | Reduce manual communication |
| **Rating System** | Feedback untuk improve service |
| **Flexible Pricing** | Support late fees & damage charges |
| **Analytics** | Understand which items popular |

---

## 6. Flow Summary

### Public User Journey
```
Browse Catalog → View Detail → Book Now → Fill Form → 
Checkout → Payment → Confirmation Email → My Rentals → 
Track Status → Pickup → Return → Rate & Review
```

### Pengurus Management Journey
```
Dashboard → Search Rental → Review → Approve/Reject → 
Start Rental (Pickup) → End Rental (Return) → 
Record Condition/Fees → Generate Invoice → View Reports
```

---

## 7. Use Cases

### Use Case 1: Customer Rental Band Set
```
1. Azis browse katalog → lihat Yamaha Drum Set
2. Klik "Rent Now"
3. Pilih 2026-04-20 s/d 2026-04-23 (3 hari)
4. Harga: 3 × Rp 300K = Rp 900K
5. Checkout dgn data diri
6. Bayar transfer → payment confirmed
7. Dapat rental code BR-260420-A7K2M9
8. Email confirmation terkirim
9. Pengurus terima rental dan approve
10. Azis pickup di toko sesuai jadwal
11. Pengurus record kondisi awal
12. Azis return paket
13. Pengurus record kondisi return
14. Lancar? Rental complete
15. Azis dapat email "Return Confirmed" + invoice
16. Azis rate & review drumset
```

### Use Case 2: Late Return Charge
```
1. Customer rental gitar 2 hari
2. Scheduled return: 2026-04-22
3. Actual return: 2026-04-23 (1 hari late)
4. Pengurus record return condition
5. Pengurus add late charge: 1 × Rp 50K = Rp 50K
6. Final invoice: original harga + Rp 50K
7. Customer bayar extra charge atau deduct dari deposit
```

### Use Case 3: Damage Report
```
1. Customer return band item dengan kerusakan
2. Pengurus inspect & record damage
3. Pengurus add damage charge: Rp 200K (miscalibration)
4. Final invoice: original harga + Rp 200K
5. Send invoice with damage detail ke customer
6. Customer bayar extra charge
```

---

## Kesimpulan

Sistem Band Rental memberikan:
- **Convenience** untuk public (easy online rental)
- **Control** untuk pengurus (approval & tracking)
- **Revenue Optimization** (late fees, damage charges, analytics)
- **Transparency** (confirmation, condition tracking, invoicing)
- **Quality** (rating system untuk feedback)
- **Automation** (availability check, notification, code generation)
