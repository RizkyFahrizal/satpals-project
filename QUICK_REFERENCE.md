# Studio Booking System - Quick Reference

## URLs

```
Admin Dashboard:  /admin/studio-bookings
Create Form:      /admin/studio-bookings/create
View Details:     /admin/studio-bookings/{id}
```

## Database

```sql
-- Table
studio_bookings

-- Key Columns
id, user_id, tanggal_booking, sesi, keperluan, status, 
catatan_admin, approved_by, approved_at, created_at, updated_at

-- Status Enum
'pending', 'approved', 'rejected', 'completed', 'cancelled'

-- Unique Constraint
(tanggal_booking, sesi)
```

## Sesi Times

| Sesi | Time |
|------|------|
| 1 | 08:00 - 11:00 |
| 2 | 11:00 - 14:00 |
| 3 | 14:00 - 17:00 |
| 4 | 17:00 - 20:00 |

## Model Usage

```php
// Get bookings for date
$bookings = StudioBooking::byDate('2026-03-28')->get();

// Check availability
if (StudioBooking::isSesiAvailable('2026-03-28', 1)) {
    // Can book sesi 1
}

// Get available sesi
$available = StudioBooking::getAvailableSesi('2026-03-28');
// Returns: [1, 3, 4] if sesi 2 is booked

// Create booking
$booking = StudioBooking::create([
    'user_id' => 1,
    'tanggal_booking' => '2026-03-28',
    'sesi' => 1,
    'keperluan' => 'Description',
    'status' => StudioBooking::STATUS_PENDING,
]);

// Approve
$booking->update([
    'status' => StudioBooking::STATUS_APPROVED,
    'approved_by' => auth()->id(),
    'approved_at' => now(),
]);

// Check if approved
if ($booking->isApproved()) {
    // ...
}

// Accessors
$booking->sesiLabel  // "Sesi 1"
$booking->sesiTime   // "08:00 - 11:00"
$booking->statusLabel // "Approved"
```

## Controller Actions

| Action | Method | Route |
|--------|--------|-------|
| View Calendar | GET | /admin/studio-bookings |
| Show Form | GET | /admin/studio-bookings/create |
| Create | POST | /admin/studio-bookings |
| View Details | GET | /admin/studio-bookings/{id} |
| Approve | PATCH | /admin/studio-bookings/{id}/approve |
| Reject | PATCH | /admin/studio-bookings/{id}/reject |
| Delete | DELETE | /admin/studio-bookings/{id} |

## Validation Rules

```php
// Create/Store
'user_npm' => 'required|exists:users,username',
'tanggal_booking' => 'required|date|after_or_equal:today',
'sesi' => 'required|integer|in:1,2,3,4',
'keperluan' => 'required|string|min:10|max:500',

// Approve
'catatan' => 'nullable|string|max:255',

// Reject
'catatan' => 'required|string|min:5|max:255',
```

## Status Workflow

```
Pending → Approved → Completed
  ↓
Rejected (can delete)
```

## Color Codes

- Yellow/Warning: Pending
- Green/Success: Approved
- Red/Danger: Rejected
- Blue/Info: Completed
- Gray/Secondary: Cancelled

## View Files

| File | Purpose |
|------|---------|
| index.blade.php | Calendar view with sesi list |
| create.blade.php | Booking creation form |
| show.blade.php | Booking details & approval |

## File Locations

```
Controllers:  app/Http/Controllers/Admin/StudioBookingController.php
Models:       app/Models/StudioBooking.php
Views:        resources/views/admin/studio-bookings/
Routes:       routes/web.php (lines 128-134)
Migration:    database/migrations/2026_03_28_155032_*
Factory:      database/factories/StudioBookingFactory.php
Tests:        tests/Feature/StudioBookingTest.php
```

## Common Tasks

### Create a Booking Manually
```
1. Click "Tambah Booking Manual"
2. Enter NPM (e.g., R2024001)
3. Select date (today or later)
4. Choose sesi (1-4)
5. Describe keperluan (min 10 chars)
6. Click "Simpan Booking"
```

### Approve a Booking
```
1. View calendar
2. Click "Detail" on booked sesi, OR
3. Click booking in "Pending Approval" sidebar
4. Click "Setujui Booking"
5. Optionally add notes
6. Confirm
```

### Reject a Booking
```
1. View booking details
2. Click "Tolak Booking"
3. Enter reason (min 5 chars)
4. Confirm rejection
```

### Check Availability
```
1. View calendar
2. Click date to see sesi view
3. Green "Tersedia" = can book
4. Shows user = already booked
```

## Important Constants

```php
StudioBooking::STATUS_PENDING = 'pending'
StudioBooking::STATUS_APPROVED = 'approved'
StudioBooking::STATUS_REJECTED = 'rejected'
StudioBooking::STATUS_COMPLETED = 'completed'
StudioBooking::STATUS_CANCELLED = 'cancelled'

StudioBooking::SESI_TIMES = [
    1 => ['label' => 'Sesi 1', 'start' => '08:00', 'end' => '11:00'],
    2 => ['label' => 'Sesi 2', 'start' => '11:00', 'end' => '14:00'],
    3 => ['label' => 'Sesi 3', 'start' => '14:00', 'end' => '17:00'],
    4 => ['label' => 'Sesi 4', 'start' => '17:00', 'end' => '20:00'],
]
```

## Query Examples

```php
// All approved bookings
$approved = StudioBooking::approved()->get();

// All pending approvals
$pending = StudioBooking::pending()->get();

// Bookings for specific user
$userBookings = StudioBooking::where('user_id', 1)->get();

// Bookings for date range
$rangeBookings = StudioBooking::whereBetween(
    'tanggal_booking', 
    ['2026-03-01', '2026-03-31']
)->get();

// Get with relationships
$bookingsWithUsers = StudioBooking::with('user', 'approvedBy')->get();

// Complex query
$bookings = StudioBooking::where('status', 'pending')
    ->with('user')
    ->orderBy('created_at', 'desc')
    ->get();
```

## Test Commands

```bash
# Run all tests
php artisan test

# Run studio booking tests only
php artisan test tests/Feature/StudioBookingTest.php

# Run specific test
php artisan test tests/Feature/StudioBookingTest.php --filter can_create_booking

# With coverage
php artisan test --coverage
```

## Artisan Commands

```bash
# Create migration
php artisan make:migration create_studio_bookings_table

# Run migration
php artisan migrate

# Rollback
php artisan migrate:rollback

# Create model
php artisan make:model StudioBooking

# Create controller
php artisan make:controller Admin/StudioBookingController -r

# Create factory
php artisan make:factory StudioBookingFactory --model=StudioBooking

# Create test
php artisan make:test StudioBookingTest --feature
```

## Error Messages (Validation)

```
'user_npm.required' => 'NPM/Username wajib diisi'
'user_npm.exists' => 'NPM/Username tidak ditemukan'
'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh di masa lalu'
'sesi.in' => 'Sesi tidak valid'
'keperluan.min' => 'Keperluan minimal 10 karakter'
```

## Middleware

```php
['auth', 'admin.access']

// Checks:
// 1. User is authenticated
// 2. User has admin or pengurus role
```

## Relationships

```php
// StudioBooking.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}

// In views:
$booking->user->name
$booking->approvedBy->name
```

## Flash Messages

```php
// Success
return redirect()->with('success', 'Booking berhasil dibuat');

// Error
return back()->with('error', 'Sesi ini sudah dipesan');

// In blade
@if (session('success'))
    {{ session('success') }}
@endif
```

## Security Features

```
✓ CSRF Protection (@csrf in forms)
✓ Route Model Binding (implicit)
✓ Middleware Authorization
✓ Input Validation (server-side)
✓ SQL Injection Prevention
✓ Database Constraints
```

## Performance Tips

```
✓ Use eager loading: with(['user', 'approvedBy'])
✓ Use scopes for filtering: ->approved()->byDate()
✓ Add database indexes on tanggal_booking, status
✓ Cache month calendar views
✓ Use pagination for large result sets
```

## Documentation Files

- `STUDIO_BOOKING_README.md` - User guide
- `STUDIO_BOOKING_IMPLEMENTATION.md` - Technical details
- `STUDIO_BOOKING_ARCHITECTURE.md` - System design
- `PHASE_2_COMPLETION_REPORT.md` - Completion report
- `QUICK_REFERENCE.md` - This file

---

**Last Updated**: March 28, 2026
**Version**: 1.0
**Status**: Complete
