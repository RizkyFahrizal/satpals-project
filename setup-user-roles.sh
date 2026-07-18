#!/bin/bash

# User Role System Migration - Setup Script
# Run this to complete the user role system update

echo "======================================"
echo "User Role System Update - Setup"
echo "======================================"
echo ""

# Check if user is in project directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this from the project root."
    exit 1
fi

echo "Step 1: Running database migration..."
echo "This will expand the role enum from 3 to 13 values."
echo ""

if php artisan migrate; then
    echo "✓ Database migration completed"
else
    echo "✗ Database migration failed"
    exit 1
fi

echo ""
echo "Step 2: Migrating existing user roles..."
echo "This will map users to correct roles based on board_members.jabatan"
echo ""

php artisan migrate:user-roles

echo ""
echo "======================================"
echo "✓ Setup Complete!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Go to http://localhost:8000/admin/users"
echo "2. Click 'Edit' on any user"
echo "3. Click 'Edit Role' link"
echo "4. Verify the new role editing page appears"
echo ""
echo "For more information, see: USER_ROLE_SYSTEM_UPDATE.md"
echo ""
