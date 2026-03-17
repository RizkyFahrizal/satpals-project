@extends('layouts.admin')

@section('title', 'Kelola Galeri Kegiatan')
@section('header', 'Kelola Galeri Kegiatan')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Kegiatan</p>
                    <p class="text-3xl font-bold">{{ $totalActivities }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Dipublikasikan</p>
                    <p class="text-3xl font-bold">{{ $publishedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-100 text-sm">Draft</p>
                    <p class="text-3xl font-bold">{{ $totalActivities - $publishedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('admin.activities.index') }}" method="GET" class="flex flex-wrap gap-3 flex-1">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari kegiatan..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'tahun', 'status']))
                    <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Reset
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.activities.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan
            </a>
        </div>
    </div>

    <!-- Activities Grid -->
    @if($activities->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-6xl mb-4">📅</div>
            <h3 class="text-xl font-bold text-gray-600">Belum ada kegiatan</h3>
            <p class="text-gray-500 mt-2">Mulai tambahkan kegiatan untuk ditampilkan di galeri</p>
            <a href="{{ route('admin.activities.create') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kegiatan Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $activity)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow group">
                    <!-- Photo -->
                    <div class="aspect-video bg-gray-100 relative overflow-hidden">
                        @if($activity->foto_1)
                            <img src="{{ asset('storage/' . $activity->foto_1) }}" 
                                 alt="{{ $activity->judul_kegiatan }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-cyan-100">
                                <span class="text-5xl">📅</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($activity->is_published)
                                <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Publik</span>
                            @else
                                <span class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-full">Draft</span>
                            @endif
                        </div>
                        
                        <!-- Year Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-2 py-1 bg-black/60 backdrop-blur-sm text-white text-xs font-semibold rounded-full">
                                {{ $activity->tanggal_kegiatan->format('Y') }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 line-clamp-2 mb-2">{{ $activity->judul_kegiatan }}</h3>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $activity->tanggal_kegiatan->translatedFormat('d F Y') }}</span>
                        </div>
                        
                        @if($activity->tempat_kegiatan)
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">{{ $activity->tempat_kegiatan }}</span>
                        </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t">
                            <a href="{{ route('admin.activities.show', $activity) }}" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                                Detail
                            </a>
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors text-center">
                                Edit
                            </a>
                            <form action="{{ route('admin.activities.toggle-publish', $activity) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-2 {{ $activity->is_published ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} text-sm font-medium rounded-lg transition-colors" title="{{ $activity->is_published ? 'Sembunyikan' : 'Publikasikan' }}">
                                    @if($activity->is_published)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
