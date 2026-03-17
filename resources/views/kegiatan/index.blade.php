@extends('layouts.app')

@section('title', 'Galeri Kegiatan - Satya Palapa')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-blue-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-20 w-48 h-48 bg-cyan-400/20 rounded-full blur-3xl animate-pulse"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center">
            <span class="inline-block px-4 py-2 bg-blue-400/20 backdrop-blur-sm rounded-full text-blue-400 text-sm font-semibold mb-6 border border-blue-400/30">
                📅 Dokumentasi Kegiatan
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Galeri <span class="bg-gradient-to-r from-blue-400 to-cyan-500 bg-clip-text text-transparent">Kegiatan</span>
            </h1>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                Dokumentasi berbagai kegiatan dan acara yang telah diselenggarakan oleh UKM Musik Satya Palapa.
            </p>
            
            <!-- Stats -->
            <div class="flex justify-center gap-8 mt-10">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400">{{ $totalActivities }}</div>
                    <div class="text-gray-400 text-sm">Total Kegiatan</div>
                </div>
                <div class="w-px bg-gray-700"></div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-400">{{ $thisYearCount }}</div>
                    <div class="text-gray-400 text-sm">Tahun {{ date('Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-gradient-to-r from-blue-500 via-blue-400 to-cyan-400 py-6 sticky top-16 z-40 shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <form action="{{ route('kegiatan.index') }}" method="GET" class="flex flex-wrap items-center justify-center gap-4">
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <input type="text" name="q" value="{{ request('q') }}" 
                    placeholder="Cari kegiatan..." 
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
            
            <!-- Filter Button -->
            <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-900 transition-all shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
            </button>
            
            @if(request()->hasAny(['q', 'tahun']))
                <a href="{{ route('kegiatan.index') }}" class="px-4 py-2.5 bg-red-500 text-white font-semibold rounded-full hover:bg-red-600 transition-all shadow-md">
                    Reset
                </a>
            @endif
        </form>
    </div>
</div>

<!-- Activities Grid -->
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        @if($activities->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-2xl font-bold text-gray-600">Belum ada kegiatan</h3>
                <p class="text-gray-500 mt-2">Kegiatan akan ditampilkan di sini setelah dipublikasikan</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($activities as $activity)
                    <a href="{{ route('kegiatan.show', $activity) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-gray-100">
                        <!-- Photo -->
                        <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 relative overflow-hidden">
                            @if($activity->foto_1)
                                <img src="{{ asset('storage/' . $activity->foto_1) }}" 
                                     alt="{{ $activity->judul_kegiatan }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-full">
                                    <span class="text-5xl">📅</span>
                                </div>
                            @endif
                            
                            <!-- Year Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-md">
                                    {{ $activity->tanggal_kegiatan->format('Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2 mb-2">
                                {{ $activity->judul_kegiatan }}
                            </h3>
                            
                            <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $activity->tanggal_kegiatan->translatedFormat('d F Y') }}</span>
                            </div>
                            
                            @if($activity->tempat_kegiatan)
                            <div class="flex items-center gap-2 text-gray-500 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">{{ $activity->tempat_kegiatan }}</span>
                            </div>
                            @endif

                            @if($activity->waktu_mulai)
                            <div class="flex items-center gap-2 text-gray-500 text-sm mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $activity->waktu_formatted }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
