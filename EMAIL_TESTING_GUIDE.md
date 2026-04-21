# 📧 Email Testing Guide

## Current Setup: Log Driver

Email dalam development disimpan ke **log file** untuk testing.

### 📍 Lihat Email yang Dikirim

**File:** `storage/logs/laravel.log`

Saat Anda approve rental dan email berhasil dikirim, anda akan melihat log seperti:

```
[2026-04-20 11:38:15] local.INFO: Sending invoice email to: customer@email.com
[2026-04-20 11:38:16] local.INFO: Invoice email sent successfully
```

### 🔍 Quick Check Command

Terminal:
```bash
tail -50 storage/logs/laravel.log | grep -i "sending invoice"
```

### ✅ Status Checklist

- ✅ Alert muncul "Invoice telah dikirim ke email pelanggan" → **Email logic berjalan**
- ✅ Log muncul "Sending invoice email to: ..." → **Email berhasil diproses**
- ✅ Log muncul "Invoice email sent successfully" → **Email terkirim ke log**

### 🚀 Untuk Production: Setup Real SMTP

Ketika deploy ke production, gunakan SMTP yang sebenarnya:

**Options:**
1. **Gmail SMTP** - Gratis, mudah setup
2. **Mailtrap** - Service testing email
3. **SendGrid/Mailgun** - Professional email service
4. **Server SMTP** - Email server sendiri

**Update .env:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ukm@example.com
MAIL_FROM_NAME="UKM Name"
```

---

## 📋 Email Features

### Invoice Approval Email Contains:

✅ Invoice PDF attachment  
✅ Band & Renter information  
✅ Performance details & duration  
✅ Final pricing with discount  
✅ Bank account for payment  
✅ WhatsApp admin link  
✅ Payment instructions  

### Email Sent When:
- ✅ Admin approves rental request (automatic)
- To: Customer email address
- Includes: Invoice PDF + payment details

---

## 🔧 Troubleshooting

**Email tidak muncul di log?**
1. Cek `.env` → `MAIL_MAILER=log` ✓
2. Cek customer email diisi saat submit form ✓
3. Lihat error di log: `tail -100 storage/logs/laravel.log | grep -i error`

**Mau real-time preview email?**
→ Install **MailHog** (untuk Windows lebih kompleks, gunakan log driver safer)

---

Last updated: 2026-04-20
