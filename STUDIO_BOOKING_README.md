# Studio Booking System - Admin UI

## Overview

The Studio Booking System is a comprehensive calendar-based management system for scheduling studio usage at SATPALS. This implementation provides administrators with a full-featured interface to manage bookings, approve requests, and track studio availability.

## Features

### 1. Calendar View
- **Interactive Month Calendar**: Navigate between months to see booking patterns
- **Date Selection**: Click any date to view detailed sesi information for that day
- **Booking Indicators**: Yellow dots show which dates have approved bookings
- **Quick Selection**: Jump to any date with the inline date picker

### 2. Sesi Management (4 Sessions Per Day)
Each day is divided into 4 sessions:
- **Sesi 1**: 08:00 - 11:00
- **Sesi 2**: 11:00 - 14:00
- **Sesi 3**: 14:00 - 17:00
- **Sesi 4**: 17:00 - 20:00

For each sesi, admins can:
- View booked user details (if booked)
- See booking keperluan (purpose)
- Quick-book empty slots with "Book Sekarang" button
- Access detailed view with single click

### 3. Booking Management

#### Create Booking
- **Manual Booking**: Admins can book on behalf of users
- **NPM Lookup**: Autocomplete search for user selection
- **Date Validation**: Can only book from today onwards
- **Keperluan Field**: Minimum 10 characters describing usage
- **Sesi Selection**: Dropdown with all 4 sessions

#### Approve/Reject Workflow
- **Pending View**: Sidebar shows all pending approvals
- **Approve Button**: Accept booking with optional notes
- **Reject Modal**: Reject with required reason
- **Timestamp Tracking**: Record who approved and when

### 4. Pending Bookings Sidebar
- **Sticky Sidebar**: Always visible while scrolling
- **Count Badge**: Shows total pending bookings
- **Quick Preview**: See user, date, sesi, and purpose
- **Fast Review**: "Review & Approve" button opens detail view
- **Auto-scrolling**: List scrolls independently

## Database Schema

### studio_bookings Table

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| user_id | bigint FK | Who is booking |
| tanggal_booking | date | Date of booking |
| sesi | integer | 1-4 |
| keperluan | text | Purpose/description |
| status | enum | pending, approved, rejected, completed, cancelled |
| catatan_admin | text | Admin notes (nullable) |
| approved_by | bigint FK | Who approved (nullable) |
| approved_at | timestamp | When approved (nullable) |
| created_at | timestamp | Created timestamp |
| updated_at | timestamp | Updated timestamp |

**Unique Constraint**: `(tanggal_booking, sesi)` - Prevents double booking

## URL Routes

All routes require authentication and admin access middleware.

### GET Routes
- `/admin/studio-bookings` - Calendar & sesi view
- `/admin/studio-bookings/create` - Booking creation form
- `/admin/studio-bookings/{booking}` - Booking details & approval

### POST Routes
- `/admin/studio-bookings` - Store new booking

### PATCH Routes
- `/admin/studio-bookings/{booking}/approve` - Approve booking
- `/admin/studio-bookings/{booking}/reject` - Reject booking

### DELETE Routes
- `/admin/studio-bookings/{booking}` - Delete booking

## Form Validations

### Create Booking Form
```php
'user_npm' => 'required|exists:users,username'      // NPM must exist
'tanggal_booking' => 'required|date|after_or_equal:today' // Can't book past
'sesi' => 'required|integer|in:1,2,3,4'            // Valid sesi
'keperluan' => 'required|string|min:10|max:500'     // Description
```

### Approve Form (Optional)
```php
'catatan' => 'nullable|string|max:255'  // Optional notes
```

### Reject Form (Required)
```php
'catatan' => 'required|string|min:5|max:255'  // Required reason
```

## Model Methods

### Scopes
```php
StudioBooking::byDate('2026-03-28')        // Get bookings for date
StudioBooking::approved()                  // Only approved
StudioBooking::pending()                   // Only pending
```

### Static Methods
```php
StudioBooking::isSesiAvailable('2026-03-28', 1)     // Boolean
StudioBooking::getAvailableSesi('2026-03-28')       // Array of sesi numbers
```

### Instance Methods
```php
$booking->isApproved()                     // Boolean
$booking->sesiLabel                        // "Sesi 1"
$booking->sesiTime                         // "08:00 - 11:00"
$booking->statusLabel                      // "Pending", "Approved", etc.
$booking->statusBadge                      // Bootstrap badge class
```

## Usage Examples

### Create a Booking Programmatically
```php
$booking = StudioBooking::create([
    'user_id' => 1,
    'tanggal_booking' => '2026-03-28',
    'sesi' => 1,
    'keperluan' => 'Latihan band untuk konser',
    'status' => StudioBooking::STATUS_PENDING,
]);
```

### Check Availability
```php
// Check single sesi
if (StudioBooking::isSesiAvailable('2026-03-28', 1)) {
    // Sesi 1 is available
}

// Get all available sesi for a date
$available = StudioBooking::getAvailableSesi('2026-03-28');
// Returns: [2, 3, 4] if sesi 1 is booked
```

### Query Bookings
```php
// Get all bookings for a specific date
$bookings = StudioBooking::byDate('2026-03-28')->get();

// Get only approved bookings
$approved = StudioBooking::approved()->get();

// Get pending bookings with user info
$pending = StudioBooking::pending()
    ->with('user')
    ->orderBy('created_at', 'desc')
    ->get();
```

### Approve a Booking
```php
$booking->update([
    'status' => StudioBooking::STATUS_APPROVED,
    'approved_by' => auth()->id(),
    'approved_at' => now(),
]);
```

## Features in Detail

### Index View (Calendar)

1. **Header Section**
   - Title and description
   - "Tambah Booking Manual" button

2. **Date Selector**
   - Inline date picker
   - Shows selected date in readable format

3. **Mini Calendar**
   - Full month view
   - Day numbers
   - Booking indicators (yellow dots)
   - Quick date navigation

4. **Sesi View**
   - 4 rows for 4 sessions
   - Each row shows:
     - Sesi label (Sesi 1, 2, 3, 4)
     - Time range
     - Booked user (if applicable)
     - "Book Sekarang" button (if empty)
     - "Detail" button (if booked)

5. **Pending Sidebar**
   - Sticky positioning
   - Lists pending bookings
   - Shows user, date, sesi, keperluan
   - "Review & Approve" button
   - Scrollable list

### Create View (Form)

1. **NPM Field**
   - Autocomplete datalist
   - Must exist in users table
   - Shows user name and NPM

2. **Date Field**
   - Date picker
   - Minimum: today
   - No maximum

3. **Sesi Dropdown**
   - All 4 options
   - Shows time range for each
   - Required field

4. **Keperluan Textarea**
   - Minimum 10 characters
   - Maximum 500 characters
   - Required field

5. **Reference Box**
   - Shows sesi times
   - Helps users understand the schedule

### Show View (Details & Approval)

1. **Booking Information**
   - Status badge with color
   - Booking ID
   - User details (name, NPM)
   - Date, sesi, time
   - Keperluan description
   - Created and updated timestamps

2. **Approval Information** (if applicable)
   - Approver name
   - Timestamp
   - Catatan/alasan

3. **Action Buttons**
   - If pending: Approve & Reject buttons
   - If not pending: Status display only
   - If pending/rejected: Delete button

## Status Color Coding

- **Pending** (Yellow): Waiting for approval - badge-warning
- **Approved** (Green): Confirmed booking - badge-success
- **Rejected** (Red): Denied booking - badge-danger
- **Completed** (Blue): Booking finished - badge-info
- **Cancelled** (Gray): User cancelled - badge-secondary

## Security Features

1. **CSRF Protection**: All forms include CSRF tokens
2. **Route Model Binding**: Automatic model injection
3. **Authorization**: Admin middleware checks access
4. **Input Validation**: All inputs validated server-side
5. **Database Constraints**: Unique constraint prevents race conditions

## Performance Optimizations

1. **Eager Loading**: Uses `with()` to prevent N+1 queries
2. **Database Scopes**: Efficient filtering at query level
3. **Index Optimization**: Indexes on frequently queried columns
4. **Pagination**: Not needed (max 4 sesi per day)
5. **Caching**: Can be added for calendar views

## Testing

### Unit Tests
```bash
php artisan test tests/Feature/StudioBookingTest.php
```

Tests included:
- Access index page
- Create booking
- Approve booking
- Reject booking
- Prevent double booking
- Prevent past date booking
- Sesi availability checking
- Get available sesi

### Manual Testing Checklist

- [ ] Login as admin/pengurus
- [ ] Navigate to Booking Studio menu
- [ ] View current month calendar
- [ ] Change date selection
- [ ] Create new booking manually
- [ ] View pending bookings
- [ ] Approve a booking
- [ ] Reject a booking
- [ ] Delete a pending booking
- [ ] Try to book past date (should fail)
- [ ] Try to double book (should fail)

## Future Enhancements

### Phase 3 - Public Views
- [ ] Public calendar view (read-only)
- [ ] Public booking form (user books own)
- [ ] Public status checking
- [ ] Email notifications

### Phase 4 - Advanced Features
- [ ] Bulk approval/rejection
- [ ] Export bookings to PDF
- [ ] Usage statistics/reports
- [ ] Booking history
- [ ] Email reminders
- [ ] Integration with other systems

### Phase 5 - Improvements
- [ ] Mobile responsive optimization
- [ ] Dark mode support
- [ ] Booking cancellation by user
- [ ] Booking modification
- [ ] Admin comments/notes system

## File Locations

```
app/
  ├── Http/Controllers/Admin/
  │   └── StudioBookingController.php       (191 lines)
  └── Models/
      └── StudioBooking.php                 (160 lines)

database/
  ├── factories/
  │   └── StudioBookingFactory.php          (70 lines)
  └── migrations/
      └── 2026_03_28_155032_create_studio_bookings_table.php

resources/views/admin/studio-bookings/
  ├── index.blade.php                       (250 lines)
  ├── create.blade.php                      (150 lines)
  └── show.blade.php                        (400 lines)

routes/
  └── web.php                               (7 routes added)

tests/Feature/
  └── StudioBookingTest.php                 (140 lines)
```

## Support & Documentation

For detailed implementation information, see:
- `STUDIO_BOOKING_IMPLEMENTATION.md` - Implementation details
- `README.md` - Project overview
- Code comments in source files

## Version

- Version: 1.0
- Implementation Date: March 28, 2026
- Status: Phase 2 Complete (Admin UI)
- Next Phase: Public Views
