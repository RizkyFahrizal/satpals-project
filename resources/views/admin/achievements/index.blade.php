@extends('layouts.admin')

@section('title', 'Kelola Galeri Prestasi - Admin Satya Palapa')

@section('header', 'Kelola Galeri Prestasi')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Prestasi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Juara 1 -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🥇</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Juara 1</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['juara_1'] }}</p>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dipublikasi</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form action="{{ route('admin.achievements.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari judul lomba, band, tempat..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Juara Filter -->
            <div class="w-full md:w-48">
                <select name="juara" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Juara</option>
                    @foreach($juaraOptions as $value => $label)
                    <option value="{{ $value }}" {{ request('juara') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Filter -->
            <div class="w-full md:w-36">
                <select name="tahun" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                    Filter
                </button>
                @if(request('search') || request('juara') || request('tahun'))
                <a href="{{ route('admin.achievements.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Prestasi</h3>
            <p class="text-sm text-gray-500">Kelola galeri prestasi UKM</p>
        </div>
        <a href="{{ route('admin.achievements.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Prestasi
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    <!-- Achievements Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($achievements as $achievement)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
            <!-- Photo -->
            <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                @if($achievement->foto_1)
                <img src="{{ asset('storage/' . $achievement->foto_1) }}" alt="{{ $achievement->judul_lomba }}" class="w-full h-full object-cover">
                @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif

                <!-- Badge Juara -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-gradient-to-r from-amber-400 to-yellow-500 text-white text-sm font-bold rounded-full shadow">
                        🏆 {{ $achievement->juara_label }}
                    </span>
                </div>

                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    @if($achievement->is_published)
                    <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Publik</span>
                    @else
                    <span class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Draft</span>
                    @endif
                </div>

                <!-- Photo Count -->
                @if(count($achievement->photos) > 1)
                <div class="absolute bottom-3 right-3">
                    <span class="px-2 py-1 bg-black/50 text-white text-xs font-semibold rounded-full">
                        📷 {{ count($achievement->photos) }} foto
                    </span>
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">
                <h4 class="font-bold text-gray-800 text-lg mb-1 line-clamp-1">{{ $achievement->judul_lomba }}</h4>
                
                @if($achievement->nama_band)
                <p class="text-yellow-600 font-semibold text-sm mb-2">🎸 {{ $achievement->nama_band }}</p>
                @endif

                <div class="space-y-1 text-sm text-gray-500 mb-3">
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $achievement->tanggal_lomba->format('d M Y') }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="line-clamp-1">{{ $achievement->tempat_lomba }}</span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.achievements.show', $achievement) }}" 
                       class="flex-1 py-2 text-center text-blue-600 hover:bg-blue-50 rounded-lg transition-colors text-sm font-medium">
                        Detail
                    </a>
                    <a href="{{ route('admin.achievements.edit', $achievement) }}" 
                       class="flex-1 py-2 text-center text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors text-sm font-medium">
                        Edit
                    </a>
                    <form action="{{ route('admin.achievements.toggle-publish', $achievement) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 text-center {{ $achievement->is_published ? 'text-gray-600 hover:bg-gray-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors text-sm font-medium">
                            {{ $achievement->is_published ? 'Hide' : 'Publish' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.achievements.destroy', $achievement) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus prestasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <p class="text-gray-500 text-lg mb-2">Belum ada data prestasi</p>
                <p class="text-gray-400 text-sm mb-4">Tambahkan prestasi UKM untuk ditampilkan di galeri</p>
                <a href="{{ route('admin.achievements.create') }}" class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Prestasi Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($achievements->hasPages())
    <div class="flex justify-center">
        {{ $achievements->links() }}
    </div>
    @endif
</div>
@endsection
