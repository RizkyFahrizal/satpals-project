@extends('layouts.app')

@section('title', 'Galeri Prestasi - Satya Palapa')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-20 w-48 h-48 bg-orange-400/20 rounded-full blur-3xl animate-pulse"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-yellow-400/20 backdrop-blur-sm rounded-full text-yellow-400 text-sm font-semibold mb-6 border border-yellow-400/30">
                🏆 Prestasi & Penghargaan
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Galeri <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">Prestasi</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                Kumpulan prestasi dan penghargaan yang telah diraih oleh UKM Musik Satya Palapa dalam berbagai kompetisi musik.
            </p>
            
            <!-- Stats -->
            <div class="flex justify-center gap-8 mt-10">
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-400">{{ $totalAchievements }}</div>
                    <div class="text-gray-400 text-sm">Total Prestasi</div>
                </div>
                <div class="w-px bg-gray-700"></div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-400">{{ $juaraCount }}</div>
                    <div class="text-gray-400 text-sm">Juara 1/2/3</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-400 py-6 sticky top-16 z-40 shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <form action="{{ route('prestasi.index') }}" method="GET" class="flex flex-wrap items-center justify-center gap-4">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" 
                    placeholder="Cari prestasi..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-full bg-white/90 backdrop-blur-sm border-0 focus:ring-2 focus:ring-gray-800 text-gray-800 placeholder-gray-500 shadow-md">
                <svg class="w-5 h-5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            
            <!-- Filter Tahun -->
            <select name="tahun" class="px-4 py-2.5 rounded-full bg-white/90 backdrop-blur-sm border-0 focus:ring-2 focus:ring-gray-800 text-gray-800 shadow-md cursor-pointer">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            
            <!-- Filter Juara -->
            <select name="juara" class="px-4 py-2.5 rounded-full bg-white/90 backdrop-blur-sm border-0 focus:ring-2 focus:ring-gray-800 text-gray-800 shadow-md cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach(\App\Models\Achievement::JUARA_OPTIONS as $key => $label)
                    <option value="{{ $key }}" {{ request('juara') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            
            <!-- Filter Button -->
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-900 transition-all shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            
            @if(request()->hasAny(['q', 'tahun', 'juara']))
                <a href="{{ route('prestasi.index') }}" class="px-4 py-2.5 bg-red-500 text-white font-semibold rounded-full hover:bg-red-600 transition-all shadow-md">
                    Reset
                </a>
            @endif
        </form>
    </div>
</div>

<!-- Achievements Grid -->
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        @if($achievements->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🏆</div>
                <h3 class="text-2xl font-bold text-gray-600">Belum ada prestasi</h3>
                <p class="text-gray-500 mt-2">Prestasi akan ditampilkan di sini setelah dipublikasikan</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($achievements as $achievement)
                    <a href="{{ route('prestasi.show', $achievement) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-gray-100">
                        <!-- Photo -->
                        <div class="aspect-video bg-gradient-to-br from-yellow-100 to-orange-100 relative overflow-hidden">
                            @if($achievement->foto_1)
                                <img src="{{ asset('storage/' . $achievement->foto_1) }}" 
                                     alt="{{ $achievement->judul_lomba }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-full">
                                    <span class="text-5xl">🏆</span>
                                </div>
                            @endif
                            
                            <!-- Badge Juara -->
                            <div class="absolute top-3 left-3">
                                @php
                                    $badgeClass = match($achievement->juara) {
                                        'juara_1' => 'bg-yellow-400 text-yellow-900',
                                        'juara_2' => 'bg-gray-300 text-gray-800',
                                        'juara_3' => 'bg-orange-400 text-orange-900',
                                        'best_performance', 'best_vocal', 'best_musician' => 'bg-purple-500 text-white',
                                        'finalis', 'semifinalis' => 'bg-blue-500 text-white',
                                        default => 'bg-gray-600 text-white'
                                    };
                                @endphp
                                <span class="px-3 py-1 {{ $badgeClass }} text-xs font-bold rounded-full shadow-md">
                                    {{ $achievement->juara_label }}
                                </span>
                            </div>
                            
                            <!-- Year Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 bg-black/60 backdrop-blur-sm text-white text-xs font-semibold rounded-full">
                                    {{ $achievement->tanggal_lomba->format('Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-2 mb-2">
                                {{ $achievement->judul_lomba }}
                            </h3>
                            
                            @if($achievement->nama_band)
                                <div class="flex items-center gap-2 text-gray-600 text-sm mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="font-medium">{{ $achievement->nama_band }}</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center gap-2 text-gray-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $achievement->tempat_lomba ?: 'Lokasi tidak tersedia' }}</span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-gray-500 text-sm mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $achievement->tanggal_lomba->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $achievements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
