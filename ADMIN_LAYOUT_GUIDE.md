# 📋 Admin Layout Guide - Satya Palapa

## Overview
Layout admin baru dengan desain responsif yang mendukung **Desktop (PC)**, **Tablet**, dan **Mobile** dengan sidebar yang dapat disembunyikan.

## Features
✅ **Responsive Design** - Terbukti optimal di semua ukuran layar  
✅ **Mobile Hamburger Menu** - Toggle sidebar di mobile  
✅ **Breadcrumb Navigation** - Navigasi hierarki yang jelas  
✅ **Professional Typography** - Title dan header yang konsisten  
✅ **Theme Consistency** - Yellow/Orange gradient sesuai brand Satya Palapa  
✅ **User Dropdown** - Menu profile dan logout di navbar  
✅ **Menu Categories** - Sidebar dengan category sections (Operasional, Administratif, dll)

---

## 📐 Layout Structure

```
┌─────────────────────────────────────────────────┐
│  📱 Mobile Menu Toggle  │ Page Title │ User Menu │
├──────────────┬──────────────────────────────────┤
│              │                                  │
│  Sidebar     │  Page Content                    │
│  (Mobile:    │  (Responsive Grid)               │
│   Hidden)    │                                  │
│              │                                  │
└──────────────┴──────────────────────────────────┘

Desktop (lg): Sidebar always visible (w-64)
Tablet (md): Sidebar visible, content takes remaining space
Mobile (sm): Sidebar hidden by default, toggle with hamburger
```

---

## 🎯 How to Use

### 1. Basic Template Structure

```blade
@extends('layouts.admin')

@section('title', 'Page Title - Admin Satya Palapa')

@section('header', 'Page Title / Menu Name')

@section('breadcrumb', 'Category > Submenu')

@section('content')
    <!-- Your page content here -->
@endsection
```

### 2. Section Definitions

#### **@section('title', '...')**
- Tampil di browser tab
- Format: `[Nama Halaman] - Admin Satya Palapa`
- Contoh: `Kelola Anggota Diklat - Admin Satya Palapa`

#### **@section('header', '...')**
- Nama besar di top navbar
- Single line, descriptive name
- Contoh: `Kelola Anggota Diklat`

#### **@section('breadcrumb', '...')**
- Navigasi hierarki di bawah header
- Format: `Category > Subcategory > Page`
- Contoh: `Operasional > Pendaftaran Diklat`

---

## 🗂️ Sidebar Menu Structure

### Kategori Menu

```
🏠 Dashboard
─────────────────────────────
📋 SISTEM
  👥 Kelola User (Super Admin only)

📋 OPERASIONAL
  📝 Pendaftaran Diklat
  👫 Data Anggota UKM
  🏢 Struktur Pengurus

📋 ADMINISTRATIF
  📄 Template Surat
  📋 Arsip Surat

📋 KEUANGAN & ASET
  💰 Kelola Keuangan (disabled)
  🎸 Persewaan Alat (disabled)
  🎤 Persewaan Band (disabled)
  🎵 Booking Studio (disabled)

📋 KONTEN
  🖼️ Galeri Kegiatan
  🏆 Galeri Prestasi
```

---

## 📱 Responsive Breakpoints

| Device | Width | Sidebar | Hamburger |
|--------|-------|---------|-----------|
| Mobile | < 768px | Hidden (overlay) | ✅ Visible |
| Tablet | 768px - 1024px | Hidden (overlay) | ✅ Visible |
| Desktop | ≥ 1024px | Always Visible | ❌ Hidden |

---

## 🎨 Color & Typography Scheme

### Colors
- **Primary**: Yellow-400 to Orange-500 (gradient)
- **Active State**: Bright yellow/orange with shadow
- **Hover State**: Light yellow background
- **Text**: Gray-800 (dark text), Gray-500 (secondary)
- **Borders**: Yellow-200 (soft yellow)

### Typography
- **Header (h1)**: 20px (lg:24px) font-bold, text-gray-800
- **Menu Items**: 14px (sm) font-medium
- **Small Text**: 12px (xs) font-normal, text-gray-500
- **User Name**: 14px (sm) font-semibold

---

## 💻 Examples

### Example 1: Dashboard
```blade
@extends('layouts.admin')
@section('title', 'Dashboard - Admin Satya Palapa')
@section('header', 'Dashboard')
@section('breadcrumb', 'Home')
@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Stats cards -->
    </div>
@endsection
```

### Example 2: Data Management (with breadcrumb)
```blade
@extends('layouts.admin')
@section('title', 'Kelola Anggota - Admin Satya Palapa')
@section('header', 'Kelola Anggota UKM')
@section('breadcrumb', 'Operasional > Data Anggota')
@section('content')
    <div class="space-y-6">
        <!-- Search & Filter -->
        <!-- Data Table -->
    </div>
@endsection
```

### Example 3: Form Page
```blade
@extends('layouts.admin')
@section('title', 'Edit Periode - Admin Satya Palapa')
@section('header', 'Edit Periode Pendaftaran')
@section('breadcrumb', 'Operasional > Pendaftaran > Edit')
@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-6">
        <!-- Form content -->
    </div>
@endsection
```

---

## ✨ Key Features Breakdown

### 1. **Mobile Hamburger Menu**
```javascript
// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
}
```
- Hamburger icon muncul pada < 1024px
- Sidebar slides in from left
- Overlay gelap (50% opacity) di latar belakang
- Click overlay untuk close sidebar

### 2. **User Dropdown**
- Avatar dengan initial user
- Show nama & role di desktop
- Dropdown menu: Profil, Pengaturan, Logout
- Always accessible di top-right

### 3. **Active Menu Indicator**
- Background gradient (yellow to orange)
- Smooth shadow effect
- Uses Laravel `request()->routeIs()` helper

### 4. **Responsive Content**
- 4px padding mobile (p-4)
- 8px padding desktop (lg:p-8)
- Responsive grid: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`

---

## 🔧 Customization

### Change Header Title
In any admin page:
```blade
@section('header', 'Your Custom Title')
```

### Change Breadcrumb
```blade
@section('breadcrumb', 'Level 1 > Level 2 > Level 3')
```

### Add New Sidebar Menu Item
Edit `resources/views/layouts/admin.blade.php` in the nav section:
```blade
<li>
    <a href="{{ route('your.route') }}" 
       class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium 
              {{ request()->routeIs('your.route') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
        <svg class="w-5 h-5 flex-shrink-0"><!-- icon --></svg>
        <span class="text-sm">Your Menu Item</span>
    </a>
</li>
```

---

## 📊 Responsive Behavior Checklist

- [ ] Test on iPhone SE (375px)
- [ ] Test on iPhone 12 (390px)
- [ ] Test on iPad (768px)
- [ ] Test on iPad Pro (1024px)
- [ ] Test on Desktop (1440px+)
- [ ] Hamburger menu appears on mobile
- [ ] Sidebar collapses on mobile
- [ ] Content padding adjusts correctly
- [ ] Text sizes scale appropriately
- [ ] User dropdown is accessible

---

## 🚀 Pro Tips

1. **Always use @section('breadcrumb')** for consistent navigation feedback
2. **Keep header titles short** (< 50 chars for mobile)
3. **Use responsive grid classes** for cards and lists
4. **Test on mobile regularly** - sidebar toggle is crucial
5. **Maintain color consistency** - use only yellow/orange from theme
6. **Use Tailwind classes** - responsive prefix (sm:, md:, lg:)

---

## 📝 File Locations

- **Main Layout**: `resources/views/layouts/admin.blade.php`
- **Admin Pages**: `resources/views/admin/*/index.blade.php`
- **Admin Forms**: `resources/views/admin/*/show.blade.php`

---

## 🎓 Learning Resources

- Tailwind CSS: https://tailwindcss.com/docs/responsive-design
- DaisyUI Components: https://daisyui.com/
- Laravel Blade: https://laravel.com/docs/11.x/blade

---

**Last Updated**: March 2026  
**Theme**: Yellow/Orange - Satya Palapa UKM  
**Version**: 2.0 (Responsive Professional Design)
