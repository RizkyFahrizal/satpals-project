#!/usr/bin/env php
<?php

/**
 * PDF Invoice System - Quick Test Guide
 * 
 * This file demonstrates how to test the PDF invoice system
 */

echo "=== PDF Invoice System - Test Guide ===\n\n";

echo "1. CREATE A BOOKING\n";
echo "   - Go to: http://localhost:8000/equipment\n";
echo "   - Add items to cart\n";
echo "   - Go to checkout\n";
echo "   - Fill in renter details\n";
echo "   - Click 'Konfirmasi Pesanan'\n\n";

echo "2. APPROVE BOOKING (Admin)\n";
echo "   - Login as admin\n";
echo "   - Go to: Admin Panel > Equipment Rental Requests\n";
echo "   - Find your booking\n";
echo "   - Click 'Setujui' button\n";
echo "   - System automatically:\n";
echo "     • Generates PDF invoice\n";
echo "     • Sends email with PDF attachment\n";
echo "     • Displays success message\n\n";

echo "3. DOWNLOAD/VIEW INVOICE\n";
echo "   Option A - From booking page:\n";
echo "   - Go to: http://localhost:8000/bookings/{id}\n";
echo "   - Click 'Download Invoice' or 'Lihat Invoice'\n\n";
echo "   Option B - Direct URL:\n";
echo "   - Download: http://localhost:8000/invoices/{id}/download\n";
echo "   - View in browser: http://localhost:8000/invoices/{id}/view\n\n";

echo "4. CHECK EMAIL (Development)\n";
echo "   Method A - Configure .env for testing:\n";
echo "   - Set: MAIL_DRIVER=log\n";
echo "   - Check: storage/logs/laravel.log\n\n";
echo "   Method B - Use Mailtrap or similar:\n";
echo "   - Configure SMTP in .env\n";
echo "   - Check inbox for approval email\n\n";

echo "5. TROUBLESHOOTING\n";
echo "   If PDF doesn't generate:\n";
echo "   - Check: config/dompdf.php is configured\n";
echo "   - Run: composer require barryvdh/laravel-dompdf\n";
echo "   - Clear caches: php artisan cache:clear\n\n";

echo "   If email doesn't send:\n";
echo "   - Check .env mail configuration\n";
echo "   - Run: php artisan config:cache\n";
echo "   - Check: storage/logs/laravel.log\n\n";

echo "6. ADMIN APPROVAL PROCESS\n";
echo "   The approve() method in EquipmentRentalRequestController:\n";
echo "   - Updates booking status to 'approved'\n";
echo "   - Calls sendApprovalEmail()\n";
echo "   - sendApprovalEmail() creates BookingApprovedMail\n";
echo "   - BookingApprovedMail generates PDF and sends email\n\n";

echo "=== KEY FILES ===\n";
echo "Controller:   app/Http/Controllers/InvoiceController.php\n";
echo "Mailable:     app/Mail/BookingApprovedMail.php\n";
echo "Invoice TPL:  resources/views/emails/invoice.blade.php\n";
echo "Email TPL:    resources/views/emails/booking_approved.blade.php\n";
echo "Admin Ctrl:   app/Http/Controllers/Admin/EquipmentRentalRequestController.php\n";
echo "Routes:       routes/web.php\n";
echo "Config:       config/dompdf.php\n\n";

echo "=== USEFUL COMMANDS ===\n";
echo "php artisan route:list              # List all routes\n";
echo "php artisan cache:clear             # Clear caches\n";
echo "php artisan tinker                  # PHP REPL for testing\n";
echo "php artisan serve                   # Start dev server\n\n";

echo "=== TEST IN TINKER ===\n";
echo "Run: php artisan tinker\n";
echo "\$booking = App\\Models\\EquipmentRentalRequest::first();\n";
echo "redirect(route('invoice.view', \$booking->id))->send();\n\n";

echo "Done! Follow the steps above to test the PDF invoice system.\n";
