# 📱 Admin Layout - Implementation Examples

Berikut adalah contoh implementasi konsisten untuk semua halaman admin Satya Palapa.

---

## 1. ✅ MEMBERS - Data Anggota UKM

### Template File
`resources/views/admin/members/index.blade.php`

```blade
@extends('layouts.admin')

@section('title', 'Data Anggota UKM - Admin Satya Palapa')

@section('header', 'Data Anggota UKM')

@section('breadcrumb', 'Operasional > Data Anggota')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Section -->
    <div class="bg-white rounded-2xl p-4 lg:p-6 shadow-sm border border-gray-100">
        <form action="{{ route('admin.members.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NPM, fakultas..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                    Filter
                </button>
                @if(request('search'))
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Add Button -->
    <a href="{{ route('admin.members.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl hover:shadow-lg transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Anggota
    </a>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-yellow-50">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="hidden md:table-cell px-4 lg:px-6 py-4 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="hidden lg:table-cell px-4 lg:px-6 py-4 text-left text-sm font-semibold text-gray-700">Fakultas</th>
                        <th class="px-4 lg:px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $index => $member)
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-4 lg:px-6 py-4 text-sm text-gray-600">{{ $members->firstItem() + $index }}</td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($member->nama_lengkap ?? 'N', 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $member->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="hidden md:table-cell px-4 lg:px-6 py-4 text-sm text-gray-600 font-mono">{{ $member->npm }}</td>
                        <td class="hidden lg:table-cell px-4 lg:px-6 py-4 text-sm text-gray-600">{{ $member->fakultas }}</td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.members.show', $member) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Yakin?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 lg:px-6 py-12 text-center">
                            <p class="text-gray-500 text-sm">Belum ada data anggota</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
        <div class="px-4 lg:px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
```

---

## 2. ✅ BOARD - Struktur Pengurus

```blade
@extends('layouts.admin')

@section('title', 'Struktur Pengurus - Admin Satya Palapa')

@section('header', 'Struktur Pengurus')

@section('breadcrumb', 'Operasional > Struktur Pengurus')

@section('content')
<!-- Card layout dengan responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($positions as $position)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-4">
            <h3 class="font-bold text-gray-900">{{ $position->name }}</h3>
        </div>
        <div class="p-6">
            @if($position->member)
                <p class="text-gray-600 text-sm mb-4">Dijabat oleh:</p>
                <p class="font-bold text-gray-800">{{ $position->member->nama_lengkap }}</p>
            @else
                <p class="text-gray-400 text-sm italic">Posisi masih kosong</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
```

---

## 3. ✅ TEMPLATES - Template Surat

```blade
@extends('layouts.admin')

@section('title', 'Template Surat - Admin Satya Palapa')

@section('header', 'Template Surat')

@section('breadcrumb', 'Administratif > Template Surat')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Buat Template Baru
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
            <div class="bg-yellow-50 p-6 aspect-square flex items-center justify-center">
                <svg class="w-16 h-16 text-yellow-300 group-hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path>
                </svg>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 truncate">{{ $template->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($template->description, 50) }}</p>
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('admin.templates.show', $template) }}" class="flex-1 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg text-center hover:bg-blue-100 transition-colors text-sm">
                        Lihat
                    </a>
                    <a href="{{ route('admin.templates.edit', $template) }}" class="flex-1 py-2 bg-yellow-50 text-yellow-600 font-semibold rounded-lg text-center hover:bg-yellow-100 transition-colors text-sm">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Belum ada template surat</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
```

---

## 4. ✅ LETTERS - Arsip Surat

```blade
@extends('layouts.admin')

@section('title', 'Arsip Surat - Admin Satya Palapa')

@section('header', 'Arsip Surat')

@section('breadcrumb', 'Administratif > Arsip Surat')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-2xl p-4 lg:p-6 shadow-sm border border-gray-100">
        <form class="flex flex-col lg:flex-row gap-4">
            <input type="text" placeholder="Cari nomor surat, penerima..." 
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
            <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 font-semibold rounded-xl transition-all">
                Cari
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full min-w-max">
            <thead class="bg-yellow-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nomor Surat</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Penerima</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($letters as $letter)
                <tr class="hover:bg-yellow-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $letter->number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $letter->recipient }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $letter->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.letters.show', $letter) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">Lihat</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada surat</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

## 5. ✅ ACTIVITIES - Galeri Kegiatan

```blade
@extends('layouts.admin')

@section('title', 'Galeri Kegiatan - Admin Satya Palapa')

@section('header', 'Galeri Kegiatan')

@section('breadcrumb', 'Konten > Galeri Kegiatan')

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.activities.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-semibold rounded-xl">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Kegiatan
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($activities as $activity)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all group">
            <div class="w-full h-48 bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center overflow-hidden">
                @if($activity->image)
                    <img src="{{ asset('storage/' . $activity->image) }}" alt="{{ $activity->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                @else
                    <svg class="w-16 h-16 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                @endif
            </div>
            <div class="p-6">
                <h3 class="font-bold text-gray-800 line-clamp-2">{{ $activity->title }}</h3>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $activity->description }}</p>
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('admin.activities.edit', $activity) }}" class="flex-1 py-2 bg-yellow-50 text-yellow-600 font-semibold rounded-lg text-center hover:bg-yellow-100 text-sm">Edit</a>
                    <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Yakin?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 text-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Belum ada kegiatan</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
```

---

## Key Responsive Patterns Used

### 1. **Grid Responsive**
```blade
<!-- 1 column on mobile, 2 on tablet, 3 on desktop -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
```

### 2. **Hidden Columns**
```blade
<!-- Hide on mobile, show on tablet and above -->
<th class="hidden md:table-cell px-6 py-4">...</th>
```

### 3. **Padding Scale**
```blade
<!-- 4px padding on mobile, 6px on tablet, 8px on desktop -->
<div class="p-4 lg:p-6 lg:p-8">
```

### 4. **Flex Wrap**
```blade
<!-- Stack on mobile, row on lg screens -->
<div class="flex flex-col lg:flex-row gap-4">
```

---

## CSS Classes Reference

| Class | Mobile | Tablet | Desktop |
|-------|--------|--------|---------|
| `block` | Yes | Yes | Yes |
| `hidden md:block` | Hidden | Yes | Yes |
| `hidden lg:block` | Hidden | Hidden | Yes |
| `p-4 lg:p-6 lg:p-8` | 4px | 4px | 8px |
| `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` | 1 col | 2 cols | 3 cols |

---

**Guidelines**: Selalu gunakan responsive prefixes (sm:, md:, lg:) untuk memastikan tampilan optimal di semua device!
