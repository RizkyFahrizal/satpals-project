# Dokumentasi Sistem Kelola Keuangan (Financial Management)

## Overview
Sistem kelola keuangan adalah fitur untuk mengelola pemasukan dan pengeluaran UKM secara terstruktur, dengan approval workflow untuk menjaga integritas data keuangan.

---

## 1. Fitur Kelola Keuangan (Pengurus/Bendahara)

### Flow Umum
1. **Dashboard** - View ringkasan keuangan UKM
2. **Input Transaksi** - Tambah pemasukan atau pengeluaran
3. **Tracking Status** - Monitor status approval
4. **Laporan Keuangan** - View analitik keuangan

### Halaman-Halaman Utama

#### A. Financial Dashboard
```
GET /admin/financial-dashboard
```

**Metrics Ditampilkan:**
- 💰 Total Pemasukan (approved only)
- 📊 Total Pengeluaran (approved only)
- 📈 Saldo Berjalan (Income - Expense)
- ⏳ Pending Transactions (belum approve)

**Filter Controls:**
```
Tanggal Range (dari-sampai)
↓
Tipe: Income / Expense / Semua
↓
Status: Approved / Pending / Rejected
↓
Expense Type: Operasional / Equipment / etc
```

**Transaction List:**
```
| Date | Description | Type | Amount | Status | Actions |
|------|-------------|------|--------|--------|---------|
| 2026-04-16 | Beli speaker | Expense | Rp 500K | ✓ Approved | View/Edit |
| 2026-04-15 | Sumbangan | Income | Rp 1M | ⏳ Pending | View |
| 2026-04-14 | Perbaikan drum | Expense | Rp 300K | ✕ Rejected | View/Edit |
```

**Status Badge:**
- ✅ **Approved** (hijau) - Sudah disetujui admin, masuk laporan
- ⏳ **Pending** (kuning) - Menunggu approval dari admin
- ✕ **Rejected** (merah) - Ditolak, bisa di-edit & resubmit

#### B. Form Input Pemasukan
```
GET /admin/income/create
POST /admin/income/store
```

**Form Fields:**
```
Judul Pemasukan (required)
Contoh: "Sumbangan anggota", "Penjualan merchandise"

Deskripsi (optional)
Contoh: "Sumbangan untuk kegiatan"

Nominal (required)
Format: Rp 1,000,000

Kategori (required)
- Sumbangan
- Penjualan
- Sponsorship
- Lainnya

Bukti/Dokumen (optional)
- Upload foto/file (jpg, png, pdf)
- Maksimal 5MB
```

**Proses Submit:**
1. User isi form & upload bukti
2. Validasi form (required fields, format nominal)
3. Jika valid: Save ke database dengan status = `pending`
4. Notification ke admin: "Ada pemasukan pending untuk diapprove"
5. User lihat status = "Pending" di dashboard

#### C. Form Input Pengeluaran
```
GET /admin/expenses/create
POST /admin/expenses/store
```

**Form Fields:**
```
Nama Pengeluaran (required)
Contoh: "Beli speaker", "Perbaikan drum"

Deskripsi (optional)

Nominal (required)

Kategori Pengeluaran (required)
- Operasional
- Equipment/Alat
- Perawatan
- Acara/Event
- Lainnya

Bukti Pengeluaran (required)
- Foto kwitansi / receipt
- Foto barang yang dibeli
- Dokumen transaksi
```

**Proses Submit:** Sama seperti pemasukan (status = pending)

#### D. Detail Transaksi & Status Tracking
```
GET /admin/income/{id}
GET /admin/expenses/{id}
```

**Display:**
```
Informasi Umum:
- Judul/Nama
- Nominal
- Kategori
- Tanggal input
- Bukti/dokumen (image preview atau download)

Status & Approval:
┌─────────────────────────────────────┐
│ Status: ⏳ PENDING                   │
│ Waiting approval dari Ketua/Wakil    │
│ Submitted: 2026-04-16 10:30         │
│ Last update: 2026-04-16 10:30       │
└─────────────────────────────────────┘

Atau jika approved:
┌─────────────────────────────────────┐
│ Status: ✅ APPROVED                 │
│ Approved by: Ketua Umum (Rizal)     │
│ Approved at: 2026-04-16 14:00       │
└─────────────────────────────────────┘

Atau jika rejected:
┌─────────────────────────────────────┐
│ Status: ✕ REJECTED                  │
│ Reason: Bukti tidak jelas            │
│ Rejected by: Bendahara (Ahmad)       │
│ Rejected at: 2026-04-16 14:00       │
│ [Button: Edit & Resubmit]           │
└─────────────────────────────────────┘
```

**Actions Tersedia:**
- **View** - Lihat detail penuh
- **Edit** (jika pending/rejected) - Edit & resubmit
- **Delete** (jika pending) - Hapus transaksi
- **Download Receipt** - Download bukti

#### E. Laporan Keuangan (Financial Report)
```
GET /admin/financial-report
```

**Summary:**
```
Period: April 2026

┌──────────────────────┐
│ Total Pemasukan      │ Rp 5,000,000
├──────────────────────┤
│ Total Pengeluaran    │ Rp 2,500,000
├──────────────────────┤
│ Saldo                │ Rp 2,500,000
└──────────────────────┘
```

**Charts:**
1. **Income vs Expense** - Bar chart perbandingan
2. **Category Breakdown** - Pie chart income by kategori
3. **Expense by Category** - Pie chart pengeluaran by kategori
4. **Monthly Trend** - Line chart trend bulanan

**Table Breakdown:**
```
Income Summary:
- Sumbangan: Rp 2M
- Penjualan: Rp 1.5M
- Sponsorship: Rp 1.5M

Expense Summary:
- Operasional: Rp 1M
- Equipment: Rp 1M
- Perawatan: Rp 500K
```

**Export Options:**
- 📄 PDF Report
- 📊 Excel Spreadsheet
- 📧 Email schedule

### Database Schema

#### incomes table
```sql
CREATE TABLE incomes (
    id BIGINT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    nominal DECIMAL(15,2) NOT NULL,
    kategori VARCHAR(50),
    bukti VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    alasan_reject TEXT,
    created_by BIGINT NOT NULL,
    approved_by BIGINT, -- Ketua/Wakil/Bendahara yang approve
    rejected_by BIGINT,
    created_at TIMESTAMP,
    approved_at TIMESTAMP,
    rejected_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (rejected_by) REFERENCES users(id)
);
```

#### expenses table
```sql
CREATE TABLE expenses (
    id BIGINT PRIMARY KEY,
    nama_pengeluaran VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    nominal DECIMAL(15,2) NOT NULL,
    kategori VARCHAR(50),
    bukti VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    alasan_reject TEXT,
    created_by BIGINT NOT NULL,
    approved_by BIGINT, -- Ketua/Wakil/Bendahara yang approve
    rejected_by BIGINT,
    created_at TIMESTAMP,
    approved_at TIMESTAMP,
    rejected_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (rejected_by) REFERENCES users(id)
);
```

### Controller Methods (Semua Pengurus)

```php
// IncomeController
public function index() // Lihat daftar income
public function create() // Form tambah income
public function store(StoreIncomeRequest $request) // Submit income
public function show(Income $income) // Lihat detail
public function edit(Income $income) // Edit form (hanya own pending)
public function update(UpdateIncomeRequest $request, Income $income)
public function destroy(Income $income) // Hapus (hanya own pending)

// ExpenseController
public function index() // Lihat daftar expense
public function create() // Form tambah expense
public function store(StoreExpenseRequest $request)
public function show(Expense $expense)
public function edit(Expense $expense) // Edit (hanya own pending)
public function update(UpdateExpenseRequest $request, Expense $expense)
public function destroy(Expense $expense) // Hapus (hanya own pending)

// FinancialDashboardController
public function index() // Dashboard dengan filter
public function filterTransactions(Request $request) // AJAX filter
public function reports() // Financial report

// Approval Controller (Khusus: Ketua/Wakil/Bendahara)
public function approvalsIndex() // Lihat pending transactions
public function approve(Income $income) // Approve income
public function reject(Income $income, Request $request) // Reject income

// Code example - Dashboard
public function index()
{
    $dateFrom = request('date_from', now()->startOfMonth());
    $dateTo = request('date_to', now());
    $type = request('type'); // 'income', 'expense', 'all'
    $status = request('status'); // 'pending', 'approved', 'rejected'
    
    $incomes = Income::approved()
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->get();
    
    $expenses = Expense::approved()
        ->whereBetween('created_at', [$dateFrom, $dateTo])
        ->get();
    
    $totalIncome = $incomes->sum('nominal');
    $totalExpense = $expenses->sum('nominal');
    $balance = $totalIncome - $totalExpense;
    
    return view('admin.financial-dashboard', [
        'incomes' => $incomes,
        'expenses' => $expenses,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'balance' => $balance,
    ]);
}
```

---

## 2. Approval Workflow (Pengurus: Ketua Umum, Wakil Ketua Umum, Bendahara)

### Flow Umum
1. **Dashboard** - View pending transactions
2. **Review** - Cek detail & bukti
3. **Approve/Reject** - Terima atau tolak dengan alasan
4. **Notification** - Kirim notifikasi ke yang input transaksi

### Siapa yang Bisa Approve?
- ✅ **Ketua Umum** - Approve semua income & expense
- ✅ **Wakil Ketua Umum** - Approve semua income & expense
- ✅ **Bendahara** - Approve semua income & expense

### Siapa yang Input Transaksi?
- Ketua Umum
- Wakil Ketua Umum
- Bendahara
- Pengurus lainnya (divisi apapun)

**Prinsip:** Minimal ada **2 orang dari role approval** (Ketua/Wakil/Bendahara) yang review sebelum transaksi included di laporan final (optional, bisa juga 1 orang sudah cukup).

### Halaman Utama

#### A. Pending Transactions Dashboard
```
GET /admin/financial-approvals
```

**Pending List:**
```
| Type | Description | Amount | Date | Status |
|------|-------------|--------|------|--------|
| Income | Sumbangan | Rp 1M | 2026-04-16 | ⏳ |
| Expense | Beli speaker | Rp 500K | 2026-04-15 | ⏳ |
```

#### B. Review & Approval
```
POST /admin/income/{id}/approve
POST /admin/income/{id}/reject
POST /admin/expenses/{id}/approve
POST /admin/expenses/{id}/reject
```

**Approve Action:**
```php
public function approve(Income $income)
{
    $this->authorize('approve', $income);
    
    $income->update([
        'status' => 'approved',
        'approved_by' => auth()->id(),
        'approved_at' => now(),
    ]);
    
    // Notification
    Notification::send($income->creator, new IncomeApproved($income));
    
    return back()->with('success', 'Income approved');
}
```

**Reject Action:**
```php
public function reject(Request $request, Income $income)
{
    $request->validate([
        'alasan_reject' => 'required|min:10|max:500',
    ]);
    
    $income->update([
        'status' => 'rejected',
        'alasan_reject' => $request->alasan_reject,
    ]);
    
    Notification::send($income->creator, new IncomeRejected($income));
    
    return back()->with('success', 'Income rejected');
}
```

---

## 3. Key Features

### A. Status Workflow
```
INPUT (Pengurus)
    ↓
PENDING (waiting approval dari Ketua/Wakil/Bendahara)
    ↓
APPROVED (included in reports) atau REJECTED (need revision)
    ↓
If REJECTED: Pengurus bisa EDIT & RESUBMIT ke approval lagi
```

### B. Calculation Logic
```
ONLY Approved transactions counted in:
- Dashboard totals
- Financial reports
- Balance calculation

Pending & Rejected:
- Visible in transaction list (to approvers)
- NOT counted in totals
- NOT included in reports
```

### C. Authorization Rules
```
Semua Pengurus (Input Transaksi):
- Create income/expense
- View all transactions
- Edit own pending transactions
- View all reports
- Can NOT approve

Ketua Umum / Wakil Ketua Umum / Bendahara (Approval Role):
- Create income/expense (seperti pengurus lainnya)
- View all transactions
- Edit own pending transactions
- Approve/Reject pending transactions (ONLY role ini yang bisa)
- View all reports
- Access to Approval Dashboard (pending transactions)
```

---

## 4. Keuntungan Sistem Kelola Keuangan

| Aspek | Manfaat |
|-------|---------|
| **Transparency** | Semua keuangan tercatat & terlacak |
| **Accountability** | Ada approval workflow, siapa approve siapa |
| **Accuracy** | Hanya approved data yg dihitung |
| **Control** | Admin bisa review & reject transaksi |
| **Analytics** | Report & chart untuk insight keuangan |
| **Audit Trail** | Semua perubahan tercatat timestamp |
| **Notification** | Real-time update status |

---

## 5. Use Cases

### Use Case 1: Pengurus Input Pengeluaran Beli Equipment
```
1. Bendahara beli speaker Rp 500K dgn bukti kwitansi
2. Input di form pengeluaran, upload foto kwitansi
3. Status = pending
4. Ketua Umum/Wakil/Bendahara review foto kwitansi
5. Ketua Umum approve
6. Muncul di laporan keuangan + diperhitungkan di balance
```

### Use Case 2: Approval Role Reject Transaksi
```
1. Wakil Ketua Umum review pemasukan "Sumbangan" Rp 1M
2. Bukti tidak jelas (foto blur)
3. Wakil click "Reject"
4. Input reason: "Bukti transaksi tidak jelas, mohon upload ulang" (min 10 karakter)
5. Pengurus yang input dapat notifikasi rejection
6. Pengurus lihat reason & click "Edit"
7. Upload bukti yang lebih jelas
8. Resubmit (kembali ke pending)
9. Bendahara review ulang & approve
```

### Use Case 3: Monthly Financial Review
```
1. Akhir bulan, Ketua Umum buka "Financial Report"
2. Lihat summary income vs expense (approved only)
3. Lihat breakdown by category
4. Export sebagai PDF
5. Share ke anggota untuk review
```

---

## 6. Workflow Sequence

### Pengurus Flow
```
Dashboard 
  → View filter options (date, type, status) 
  → Click filter 
  → See filtered transactions 
  → Click "Add Income/Expense" 
  → Fill form 
  → Upload bukti 
  → Submit 
  → See status = Pending 
  → Wait for approval 
  → Status changes to Approved/Rejected
```

### Admin Flow
```
Dashboard 
  → See pending count 
  → Click pending transaction 
  → Review details & bukti 
  → Click Approve/Reject 
  → See success message 
  → Notification sent to pengurus 
  → View reports
```

---

## Kesimpulan

Sistem Kelola Keuangan memberikan:
- **Structure** - Terstruktur dengan approval workflow
- **Transparency** - Semua orang lihat status transaksi
- **Control** - Admin punya kontrol penuh
- **Analytics** - Insight keuangan UKM
- **Auditability** - Lengkap dengan audit trail
- **Accountability** - Jelas siapa melakukan apa
