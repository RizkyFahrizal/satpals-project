# 🔄 User Role System Update - Implementation Guide

## Overview
Implemented a new role editing system for users with a dedicated page for role management. Updated the enum in database to support 12+ roles.

## What Was Done

### 1. ✅ Created Edit Role Form
- **File**: `resources/views/admin/users/edit-role.blade.php`
- **Features**:
  - Display current role at the top
  - Radio button selection for all available roles (except super_admin)
  - Clean, organized layout with role descriptions
  - Responsive grid layout

### 2. ✅ Added New Route
- **Route**: `GET /admin/users/{user}/edit-role` → `admin.users.edit-role`
- **Purpose**: Shows the edit role form
- **Handler**: `UserController@editRole()`

### 3. ✅ Updated User Main Edit Page
- **File**: `resources/views/admin/users/edit.blade.php`
- **Changes**:
  - Removed role combo box (60+ lines)
  - Added read-only role display with yellow info box
  - Added "Edit Role" link pointing to edit-role page
  - Simplified UI - focus on basic user info only

### 4. ✅ Created Data Migration Command
- **File**: `app/Console/Commands/MigrateUserRoles.php`
- **Purpose**: Migrate existing users to correct roles based on board_members.jabatan
- **Command**: `php artisan migrate:user-roles`
- **Options**: `--force` flag to skip confirmation

### 5. ✅ Database Migration Ready
- **File**: `database/migrations/2026_05_10_000000_update_users_role_enum.php`
- **Purpose**: Expand role enum from 3 to 13 values
- **Status**: Created but NOT YET RUN

## Required Steps (Do In Order)

### Step 1: Run Database Migration
Expand the role enum in the database:
```bash
php artisan migrate
```

**What it does**: Updates the `users.role` column enum to support all 12 new roles.

### Step 2: Migrate Existing User Data
Map existing users to their correct roles based on board_members:
```bash
php artisan migrate:user-roles
```

**Options**:
- Without confirmation: `php artisan migrate:user-roles --force`

**What it does**:
- For each user, finds their active board_member record
- Maps `board_members.jabatan` to the corresponding role
- Updates user.role to match
- Sets role to 'public' if no active board member found
- Preserves super_admin roles (doesn't change them)

### Step 3: Verify Everything Works
1. Go to `/admin/users`
2. Click "Edit" on any user
3. Verify role shows as read-only display (yellow box)
4. Click "Edit Role" link
5. You should see the new edit-role page
6. Select a new role and save
7. Return to users list - role should be updated

## Role Mapping

When running `migrate:user-roles`, users are mapped as follows:

| Board Member Jabatan | User Role | Label |
|---|---|---|
| ketua_umum | ketua_umum | Ketua Umum |
| wakil_ketua_umum | wakil_ketua_umum | Wakil Ketua Umum |
| bendahara | bendahara | Bendahara |
| sekretaris | sekretaris | Sekretaris |
| mpa | mpa | Ketua Majelis Perwakilan Anggota |
| band | band | Subsie Band |
| peralatan | peralatan | Subsie Peralatan |
| humas | humas | Subsie Humas |
| pdd | pdd | Subsie PDD |
| kesekretariatan | kesekretariatan | Kesekretariatan |
| (no board_member) | public | Public |
| super_admin | super_admin | Super Admin (unchanged) |

## Files Modified

```
✅ routes/web.php
   - Added GET route for edit-role form

✅ app/Http/Controllers/UserController.php
   - Added editRole() method to show form
   - Updated updateRole() with new logic

✅ resources/views/admin/users/edit.blade.php
   - Removed role combo box
   - Added read-only role display

✅ resources/views/admin/users/edit-role.blade.php
   - NEW file: Role selection form

✅ app/Console/Commands/MigrateUserRoles.php
   - NEW file: Data migration command

✅ database/migrations/2026_05_10_000000_update_users_role_enum.php
   - Already created: Enum expansion
```

## What Changed in UI

### Before (Old Edit Page)
```
Edit User
- Name field
- Email field
- Role combo box (60+ lines, radio buttons)
- Password field
- Buttons
```

### After (New Edit Page)
```
Edit User
- Name field
- Email field
- Role (read-only) - yellow box with "Edit Role" link
- Password field
- Buttons
```

### New Edit Role Page
```
Edit Role User
- User info header
- Current role display (blue box)
- Radio button selection for all roles
- Role descriptions
- Info box with warning
- Save / Cancel buttons
```

## Troubleshooting

### Error: "SQLSTATE[01000]: Data truncated for column 'role'"
**Solution**: You need to run `php artisan migrate` first to expand the enum.

### "Edit Role" link not working
**Solution**: Make sure you added the new route to `routes/web.php`:
```php
Route::get('/users/{user}/edit-role', [UserController::class, 'editRole'])->name('users.edit-role');
```

### User role not updating after migration
**Solution**: 
1. Verify the user has an active board_member record
2. Check that board_members.jabatan matches one of the mapped values
3. Try running `php artisan migrate:user-roles --force` again

## Authorization

- Only **Super Admin** can access user management
- **Super Admin role cannot be changed** via the UI (protected in both editRole and updateRole methods)
- Other user roles can be changed freely
- Changes take effect immediately after saving

## Next Steps

After implementation:
1. Test the edit-role page thoroughly
2. Verify all users are assigned correct roles
3. Test login with different roles to ensure access control works
4. Monitor for any database or validation errors
5. Consider creating backups before bulk data migration

## Technical Notes

- Role constants defined in `app/Models/User.php`
- Role validation happens in UserController
- Database enum migration uses raw SQL (MySQL specific)
- Command includes confirmation prompt for safety
- All changes are logged in console output

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review the console output from `migrate:user-roles`
3. Check database for board_members records
4. Verify role enum was expanded with `DESCRIBE users;`
