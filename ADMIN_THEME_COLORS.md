# 🎨 Admin Theme & Color Customization Guide

## Current Theme: Satya Palapa (Yellow/Orange)

### Brand Colors
```
Primary: Yellow-400 (#FACC15)
Secondary: Orange-500 (#F97316)
Gradient: from-yellow-400 to-orange-500
```

### Color Palette

| Element | Color Class | Usage |
|---------|-------------|-------|
| Active Menu Item | `from-yellow-400 to-orange-500` | Selected navigation |
| Hover State | `hover:bg-yellow-50` | Menu hover effect |
| Borders | `border-yellow-200` | Sidebar, card borders |
| Badge/Tag | `bg-yellow-100 text-yellow-800` | Status indicators |
| Button Primary | `bg-yellow-400 hover:bg-yellow-500` | Main actions |
| Button Secondary | `bg-gray-100 hover:bg-gray-200` | Secondary actions |
| Success | `text-green-600 bg-green-50` | Positive status |
| Warning | `text-yellow-600 bg-yellow-50` | Alert status |
| Danger | `text-red-600 bg-red-50` | Error/Delete status |

---

## 🎯 Pre-Built Color Combinations

### Status Badge Colors

#### Pending/Waiting
```blade
<span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold text-sm">
    ⏳ Menunggu
</span>
```

#### Approved/Success
```blade
<span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold text-sm">
    ✅ Diterima
</span>
```

#### Rejected/Danger
```blade
<span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-semibold text-sm">
    ❌ Ditolak
</span>
```

#### Processing/Info
```blade
<span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold text-sm">
    🔄 Diproses
</span>
```

---

## 📊 Data Visualization Colors

### Chart Colors (Chart.js)
```javascript
// Primary colors for charts
const chartColors = {
    primary: '#FACC15',      // Yellow-400
    secondary: '#F97316',    // Orange-500
    success: '#22C55E',      // Green-500
    danger: '#EF4444',       // Red-500
    warning: '#EAB308',      // Yellow-500
    info: '#3B82F6',         // Blue-500
};

// Example datasets
const datasets = [
    {
        label: 'Pemasukan',
        data: [65, 59, 80, 81, 56, 55, 40],
        borderColor: '#FACC15',
        backgroundColor: 'rgba(250, 204, 21, 0.1)',
        tension: 0.4,
    },
    {
        label: 'Pengeluaran',
        data: [28, 48, 40, 19, 86, 27, 90],
        borderColor: '#F97316',
        backgroundColor: 'rgba(249, 115, 22, 0.1)',
        tension: 0.4,
    },
];
```

---

## 🎨 Card & Component Colors

### Info Cards
```blade
<!-- Blue Info Card -->
<div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-blue-500">
    <h3 class="text-lg font-semibold text-blue-900">Information</h3>
    <p class="text-blue-700 mt-2">Card content here</p>
</div>

<!-- Green Success Card -->
<div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-green-500">
    <h3 class="text-lg font-semibold text-green-900">Success</h3>
    <p class="text-green-700 mt-2">Card content here</p>
</div>

<!-- Red Danger Card -->
<div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-red-500">
    <h3 class="text-lg font-semibold text-red-900">Danger</h3>
    <p class="text-red-700 mt-2">Card content here</p>
</div>
```

---

## 🔘 Button Styles

### Primary Button (Yellow/Orange)
```blade
<button class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl hover:shadow-lg transition-all">
    Primary Action
</button>
```

### Secondary Button (Gray)
```blade
<button class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
    Secondary Action
</button>
```

### Danger Button (Red)
```blade
<button class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all">
    Delete
</button>
```

### Success Button (Green)
```blade
<button class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl transition-all">
    Approve
</button>
```

---

## 🖼️ Icon + Color Combinations

### Icon with Gradient Background

```blade
<!-- Blue Gradient -->
<div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- Icon SVG -->
    </svg>
</div>

<!-- Green Gradient -->
<div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
    <svg class="w-6 h-6 text-white"><!-- Icon --></svg>
</div>

<!-- Purple Gradient -->
<div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
    <svg class="w-6 h-6 text-white"><!-- Icon --></svg>
</div>

<!-- Orange Gradient -->
<div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
    <svg class="w-6 h-6 text-white"><!-- Icon --></svg>
</div>
```

---

## 📈 Statistics Card Colors

```blade
<!-- Total Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium mb-1">Total Items</p>
            <h2 class="text-3xl font-bold text-gray-800">{{ $total }}</h2>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Pending Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium mb-1">Pending Items</p>
            <h2 class="text-3xl font-bold text-yellow-600">{{ $pending }}</h2>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Completed Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium mb-1">Completed Items</p>
            <h2 class="text-3xl font-bold text-green-600">{{ $completed }}</h2>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Rejected/Error Card -->
<div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium mb-1">Rejected Items</p>
            <h2 class="text-3xl font-bold text-red-600">{{ $rejected }}</h2>
        </div>
        <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>
```

---

## 🎯 Alert/Toast Colors

```blade
<!-- Success Alert -->
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
    <p class="font-semibold">✅ Success!</p>
    <p class="text-sm">Your action was completed successfully.</p>
</div>

<!-- Error Alert -->
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
    <p class="font-semibold">❌ Error!</p>
    <p class="text-sm">Something went wrong. Please try again.</p>
</div>

<!-- Warning Alert -->
<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-xl">
    <p class="font-semibold">⚠️ Warning!</p>
    <p class="text-sm">Please review before proceeding.</p>
</div>

<!-- Info Alert -->
<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-xl">
    <p class="font-semibold">ℹ️ Information</p>
    <p class="text-sm">This is an informational message.</p>
</div>
```

---

## 🎨 Advanced: Custom Theme Variables

To maintain consistency, you can define theme variables at the top of your base layout:

```php
<?php
// In admin layout or config file
$theme = [
    'primary' => 'from-yellow-400 to-orange-500',
    'primary_hover' => 'hover:from-yellow-500 hover:to-orange-600',
    'border' => 'border-yellow-200',
    'bg_light' => 'bg-yellow-50',
    'text_primary' => 'text-gray-800',
    'text_secondary' => 'text-gray-500',
    'shadow' => 'shadow-sm',
    'rounded' => 'rounded-2xl',
];
?>
```

Then use in templates:
```blade
<button class="px-6 py-3 bg-gradient-to-r {{ $theme['primary'] }} text-gray-900 font-semibold {{ $theme['rounded'] }}">
    Button
</button>
```

---

## 📋 Color Quick Reference

| Purpose | Tailwind Class | Hex |
|---------|----------------|-----|
| Primary | yellow-400 | #FACC15 |
| Primary Hover | orange-500 | #F97316 |
| Border | yellow-200 | #FEF08A |
| Light Background | yellow-50 | #FFFBEB |
| Success | green-600 | #16A34A |
| Warning | yellow-600 | #CA8A04 |
| Error | red-600 | #DC2626 |
| Info | blue-600 | #2563EB |
| Text Dark | gray-800 | #1F2937 |
| Text Light | gray-500 | #6B7280 |

---

**💡 Tip**: Selalu gunakan Tailwind color palette untuk konsistensi. Hindari warna custom yang tidak ada di palette!
