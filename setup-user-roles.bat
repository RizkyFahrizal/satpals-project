@echo off
REM User Role System Migration - Setup Script (Windows)
REM Run this to complete the user role system update

echo.
echo ======================================
echo User Role System Update - Setup
echo ======================================
echo.

REM Check if artisan file exists
if not exist "artisan" (
    echo Error: artisan file not found. Please run this from the project root.
    pause
    exit /b 1
)

echo Step 1: Running database migration...
echo This will expand the role enum from 3 to 13 values.
echo.

php artisan migrate
if %errorlevel% neq 0 (
    echo Database migration failed!
    pause
    exit /b 1
)

echo Database migration completed
echo.
echo Step 2: Migrating existing user roles...
echo This will map users to correct roles based on board_members.jabatan
echo.

php artisan migrate:user-roles

echo.
echo ======================================
echo Setup Complete!
echo ======================================
echo.
echo Next steps:
echo 1. Go to http://localhost:8000/admin/users
echo 2. Click 'Edit' on any user
echo 3. Click 'Edit Role' link
echo 4. Verify the new role editing page appears
echo.
echo For more information, see: USER_ROLE_SYSTEM_UPDATE.md
echo.
pause
