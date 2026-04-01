# Studio Booking System - Architecture & Implementation Guide

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     WEB BROWSER                             │
│                 (Admin Dashboard)                           │
└────────────────────────────┬────────────────────────────────┘
                             │
                    Routes (web.php)
                             │
        ┌────────────────────┼────────────────────┐
        │                    │                    │
   ┌────▼─────┐     ┌───────▼────────┐   ┌──────▼──────┐
   │  Create  │     │     Index      │   │    Show     │
   │  (Form)  │     │   (Calendar)   │   │  (Details)  │
   └────┬─────┘     └───────┬────────┘   └──────┬──────┘
        │                   │                    │
        └──────────────────┬┴────────────────────┘
                           │
                StudioBookingController
                           │
        ┌──────────────────┼──────────────────┐
        │        │         │        │         │
    ┌───▼──┐ ┌──▼──┐ ┌───▼──┐ ┌──▼──┐ ┌──▼──┐
    │store │ │show │ │apprv │ │rejct│ │dele │
    └───┬──┘ └──┬──┘ └───┬──┘ └──┬──┘ └──┬──┘
        │       │        │       │       │
        └───────┴────────┴───────┴───────┘
                       │
                StudioBooking Model
                       │
        ┌──────────────┼──────────────┐
        │              │              │
    ┌───▼─────┐  ┌────▼────┐  ┌─────▼────┐
    │Relations│  │  Scopes │  │ Accessors│
    │-user()  │  │-byDate()│  │-sesiLabel│
    │-apprv() │  │-approved│  │-sesiTime │
    └─────────┘  │-pending │  │-statusLbl│
                 └─────────┘  └──────────┘
                       │
                   Database
                       │
            ┌──────────┴──────────┐
            │                     │
      ┌─────▼─────┐      ┌────────▼────────┐
      │   users   │      │ studio_bookings │
      │  (guests) │      │   (bookings)    │
      └───────────┘      └─────────────────┘
```

## Data Flow Diagram

### 1. Viewing Calendar (GET /admin/studio-bookings)
```
User clicks "Booking Studio" in sidebar
                    ↓
         Routes to admin.studio-bookings.index
                    ↓
         StudioBookingController@index()
                    ↓
    Gets selected date from request (or today)
                    ↓
    Queries database:
    - Bookings for selected date (with relationships)
    - Bookings for entire month (for calendar dots)
    - All pending bookings (for sidebar)
                    ↓
    Builds sesiData array with availability
                    ↓
    Renders index.blade.php with:
    - Calendar grid
    - 4 sesi rows
    - Pending bookings sidebar
                    ↓
    User sees interactive calendar view
```

### 2. Creating Booking (POST /admin/studio-bookings)
```
Admin clicks "Tambah Booking Manual"
                    ↓
         Routes to admin.studio-bookings.create
                    ↓
     StudioBookingController@create()
                    ↓
    Renders create.blade.php with:
    - NPM autocomplete list (all users)
    - Date picker (min = today)
    - Sesi dropdown
    - Keperluan textarea
                    ↓
    Admin fills form & submits
                    ↓
    POST to admin.studio-bookings
                    ↓
    StudioBookingController@store()
                    ↓
    Validates input:
    - NPM exists? ✓
    - Date not past? ✓
    - Sesi valid (1-4)? ✓
    - Keperluan 10+ chars? ✓
                    ↓
    Checks availability:
    StudioBooking::isSesiAvailable($date, $sesi)
                    ↓
    If available:
    - Gets user by username
    - Creates booking (status = pending)
    - Redirects to index with success message
                    ↓
    If not available or validation fails:
    - Redirects back with error message
```

### 3. Approving Booking (PATCH /admin/studio-bookings/{id}/approve)
```
Admin clicks "Review & Approve" or "Setujui Booking"
                    ↓
    Routes to admin.studio-bookings.show
                    ↓
    StudioBookingController@show()
                    ↓
    Loads booking with relationships
    (user, approvedBy)
                    ↓
    Renders show.blade.php
    Shows booking details & approval form
                    ↓
    Admin clicks "Setujui Booking" button
                    ↓
    PATCH to admin.studio-bookings/{id}/approve
                    ↓
    StudioBookingController@approve()
                    ↓
    Validates:
    - Booking must be pending
    - Optional catatan (max 255 chars)
                    ↓
    Updates booking:
    - status = 'approved'
    - approved_by = current user ID
    - approved_at = now()
    - catatan_admin = provided notes
                    ↓
    Redirects back with success message
                    ↓
    Booking now shows as approved
    (Status badge changes to green)
```

### 4. Rejecting Booking (PATCH /admin/studio-bookings/{id}/reject)
```
Admin clicks "Tolak Booking" button
                    ↓
    Rejection modal appears
    (required catatan field)
                    ↓
    Admin fills reason & confirms
                    ↓
    PATCH to admin.studio-bookings/{id}/reject
                    ↓
    StudioBookingController@reject()
                    ↓
    Validates:
    - Booking must be pending
    - Catatan required (min 5 chars)
                    ↓
    Updates booking:
    - status = 'rejected'
    - approved_by = current user ID
    - approved_at = now()
    - catatan_admin = rejection reason
                    ↓
    Redirects back with success message
                    ↓
    Booking now shows as rejected
    (Status badge changes to red)
```

## Request/Response Cycle

### Example: Create Booking Request
```http
POST /admin/studio-bookings HTTP/1.1

Form Data:
- user_npm=R2024001
- tanggal_booking=2026-03-28
- sesi=1
- keperluan=Latihan band untuk keperluan konser
- _token=xxxxxxxxxxxx

↓

HTTP/1.1 302 Found
Location: /admin/studio-bookings
Set-Cookie: LARAVEL_SESSION=...

Flash Data:
- success=Booking berhasil dibuat dan menunggu approval
```

### Example: Approve Booking Request
```http
PATCH /admin/studio-bookings/1/approve HTTP/1.1

Form Data:
- catatan=Disetujui, sudah cek ketersediaan
- _token=xxxxxxxxxxxx
- _method=PATCH

↓

HTTP/1.1 302 Found
Location: /admin/studio-bookings/1
Set-Cookie: LARAVEL_SESSION=...

Flash Data:
- success=Booking berhasil di-approve
```

## Database Query Optimization

### 1. Index View Queries
```sql
-- Get bookings for selected date with relationships
SELECT * FROM studio_bookings
WHERE tanggal_booking = '2026-03-28'
INNER JOIN users ON studio_bookings.user_id = users.id
LEFT JOIN users AS approvers ON studio_bookings.approved_by = approvers.id
ORDER BY sesi;

-- Get bookings for entire month (for calendar indicators)
SELECT tanggal_booking, COUNT(*) as booking_count
FROM studio_bookings
WHERE tanggal_booking BETWEEN '2026-03-01' AND '2026-03-31'
AND status = 'approved'
GROUP BY tanggal_booking;

-- Get pending bookings (for sidebar)
SELECT * FROM studio_bookings
WHERE status = 'pending'
INNER JOIN users ON studio_bookings.user_id = users.id
ORDER BY created_at DESC;
```

### 2. Availability Check Query
```sql
-- Check if sesi is available
SELECT COUNT(*) FROM studio_bookings
WHERE tanggal_booking = '2026-03-28'
AND sesi = 1
AND status = 'approved';
-- Returns: 0 (available) or >0 (not available)
```

### 3. Unique Constraint
```sql
-- Database enforces this:
UNIQUE KEY unique_booking (tanggal_booking, sesi)
-- Prevents duplicate (date, sesi) pairs
```

## Code Structure

### Controller Methods - Responsibilities

```php
class StudioBookingController {
    
    // 1. Display
    public function index()     → Query + pass data → render calendar view
    public function show()      → Load + eager load → render detail view
    public function create()    → Get request params → render form
    
    // 2. Process
    public function store()     → Validate → check availability → save
    public function approve()   → Check pending → update status → redirect
    public function reject()    → Check pending → update + reason → redirect
    public function destroy()   → Check status → delete → redirect
}
```

### Model - Business Logic

```php
class StudioBooking extends Model {
    
    // Relationships
    user()          → who booked (FK to users.id)
    approvedBy()    → who approved (FK to users.id)
    
    // Accessors (computed attributes)
    sesiLabel       → "Sesi 1"
    sesiTime        → "08:00 - 11:00"
    statusLabel     → "Pending", "Approved", etc
    statusBadge     → "badge-warning", "badge-success", etc
    
    // Scopes (reusable queries)
    byDate($date)   → filter by tanggal_booking
    approved()      → filter by status = 'approved'
    pending()       → filter by status = 'pending'
    
    // Static Methods (utility functions)
    isSesiAvailable()  → check if slot taken
    getAvailableSesi() → get array of free slots
    
    // Instance Methods
    isApproved()    → status == 'approved'
}
```

## Validation Flow

```
Request Input
    ↓
FormRequest or inline validation
    ↓
┌──────────────────────────────────┐
│ Check NPM exists in users table  │
│ Check date >= today              │
│ Check sesi in [1,2,3,4]          │
│ Check keperluan length           │
│ Check availability               │
└──────────────────────────────────┘
    ↓
    ├─→ Passes:  Create/Update & Redirect with success
    │
    └─→ Fails:   Redirect back with error messages
```

## Session State Management

```
Request → Response → Flash Data → Next Request → Next Response
  ↓
Booking created
  ↓
Redirect with success flash
  ↓
User sees success message (displayed once)
  ↓
Message disappears after page refresh
```

## Security Implementation

### CSRF Protection
```blade
<form method="POST" action="/admin/studio-bookings">
    @csrf  ← Token added here
    ...
</form>
```

### Authorization Middleware
```php
Route::prefix('admin')
    ->middleware(['auth', 'admin.access'])
    ->group(function() {
        // Only authenticated users with admin role can access
    });
```

### Input Validation
```php
// Server-side validation (no relying on client)
$validated = $request->validate([
    'user_npm' => 'required|exists:users,username',
    'tanggal_booking' => 'required|date|after_or_equal:today',
    'sesi' => 'required|integer|in:1,2,3,4',
    'keperluan' => 'required|string|min:10|max:500',
]);
```

### Route Model Binding
```php
// Laravel automatically finds or throws 404
public function approve(StudioBooking $booking)
{
    // $booking is automatically injected
    // Invalid ID returns 404, not an error
}
```

## Performance Considerations

### Query Count
- Index view: 3 queries (bookings for date, month, pending)
- Create view: 1 query (get users for datalist)
- Show view: 1-2 queries (load booking + relationships)
- Store: 2-3 queries (check availability, get user, create booking)
- Approve/Reject: 1 query (update booking)

### Optimization Strategies
```php
// ✓ GOOD: Eager loading
StudioBooking::with(['user', 'approvedBy'])->get();

// ✗ BAD: N+1 query problem
foreach($bookings as $booking) {
    echo $booking->user->name; // Query per iteration!
}
```

### Potential Bottlenecks
- Large month views (1000+ bookings) → Add pagination
- Multiple date range queries → Add caching
- User lookup autocomplete → Add throttling

## Testing Strategy

### Unit Tests
- Model methods (isSesiAvailable, getAvailableSesi)
- Accessor attributes (sesiLabel, statusLabel)
- Scope filters (byDate, approved, pending)

### Feature Tests
- Access control (auth + admin.access middleware)
- Form validation (all invalid inputs)
- CRUD operations (create, read, update, delete)
- Business logic (double booking prevention)
- Status workflows (pending → approved → completed)

### Integration Tests
- Full request/response cycle
- Database state changes
- Flash message display
- Redirect chains

## Extension Points

### For Adding Features:
1. **Notifications** → Add after approve/reject
2. **Auditing** → Log state changes
3. **Reporting** → Query aggregations
4. **API** → Add API routes for mobile
5. **Webhooks** → Send to external systems

### For Customization:
1. **View Design** → Modify blade templates
2. **Validation Rules** → Update in controller
3. **Status Workflows** → Add more states
4. **Sesi Times** → Modify SESI_TIMES constant
5. **Notifications** → Add event listeners

## File Dependencies

```
StudioBookingController
    ├── uses: StudioBooking model
    ├── uses: User model
    ├── renders: admin.studio-bookings.index
    ├── renders: admin.studio-bookings.create
    └── renders: admin.studio-bookings.show

StudioBooking model
    ├── table: studio_bookings
    ├── relations to: User model
    └── uses: Carbon (dates)

Views
    ├── use: StudioBooking constants
    ├── use: Carbon for date formatting
    └── use: Bootstrap classes for styling
```

## Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Compile assets: `php artisan asset:publish`
- [ ] Test routes: Check `/admin/studio-bookings`
- [ ] Test CRUD: Create, read, approve, reject, delete
- [ ] Check permissions: Verify admin middleware works
- [ ] Monitor logs: Check for any errors in logs/
- [ ] Backup database: Before going live

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-03-28 | Initial implementation - Admin UI complete |
| 1.1 (planned) | TBD | Public views + email notifications |
| 1.2 (planned) | TBD | Bulk operations + reporting |
