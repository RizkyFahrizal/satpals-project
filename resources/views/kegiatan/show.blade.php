@extends('layouts.app')

@section('title', $activity->judul_kegiatan . ' - Satya Palapa')

@section('content')
<!-- Hero Section with Gradient -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-blue-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-48 h-48 bg-cyan-400/20 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Galeri Kegiatan
        </a>
        
        <span class="inline-block px-4 py-2 bg-blue-500/20 backdrop-blur-sm rounded-full text-blue-400 text-sm font-semibold mb-4 border border-blue-500/30">
            📅 {{ $activity->tanggal_kegiatan->translatedFormat('d F Y') }}
        </span>
        
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
            {{ $activity->judul_kegiatan }}
        </h1>
        
        <div class="flex flex-wrap items-center gap-6 text-gray-300">
            @if($activity->tempat_kegiatan)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-cyan-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                </div>
                <span>{{ $activity->tempat_kegiatan }}</span>
            </div>
            @endif
            
            @if($activity->waktu_mulai)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-yellow-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span>{{ $activity->waktu_formatted }}</span>
            </div>
            @endif

            @if($activity->ketua_pelaksana)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span>Ketua Pelaksana: <strong>{{ $activity->ketua_pelaksana }}</strong></span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Photo Gallery -->
                @if(count($activity->photos) > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6 bg-gradient-to-r from-blue-500 to-cyan-500">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            📷 Dokumentasi Kegiatan
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Main Image Display -->
                        <div class="mb-4">
                            <img id="mainImage" 
                                 src="{{ asset('storage/' . $activity->photos[0]) }}" 
                                 alt="{{ $activity->judul_kegiatan }}"
                                 class="w-full h-80 md:h-[450px] object-cover rounded-xl shadow-lg cursor-pointer transition-all"
                                 onclick="openLightbox(this.src)">
                        </div>
                        
                        <!-- Thumbnail Grid -->
                        @if(count($activity->photos) > 1)
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($activity->photos as $index => $photo)
                                <div class="aspect-video rounded-lg overflow-hidden cursor-pointer border-2 transition-all hover:border-blue-500 {{ $index === 0 ? 'border-blue-500 ring-2 ring-blue-300' : 'border-transparent' }}"
                                     onclick="changeMainImage('{{ asset('storage/' . $photo) }}', this)">
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         alt="Foto {{ $index + 1 }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform">
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Tujuan Kegiatan -->
                @if($activity->tujuan_kegiatan)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6 bg-gradient-to-r from-emerald-500 to-teal-500">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            🎯 Tujuan Kegiatan
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $activity->tujuan_kegiatan }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Deskripsi Kegiatan -->
                @if($activity->deskripsi)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-500">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            📝 Deskripsi Kegiatan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($activity->deskripsi)) !!}
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Kepanitiaan -->
                @if($activity->ketua_pelaksana || (is_array($activity->divisi) && count($activity->divisi) > 0))
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="p-6 bg-gradient-to-r from-amber-500 to-orange-500">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            👥 Kepanitiaan
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Ketua Pelaksana -->
                        @if($activity->ketua_pelaksana)
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 mb-6 border border-amber-200">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-amber-600 font-medium">Ketua Pelaksana</p>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $activity->ketua_pelaksana }}</h3>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Divisi -->
                        @if(is_array($activity->divisi) && count($activity->divisi) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Divisi-divisi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($activity->divisi as $divisi)
                                    @if(!empty($divisi['nama_divisi']))
                                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $divisi['nama_divisi'] }}</p>
                                                @if(!empty($divisi['ketua_divisi']))
                                                <p class="text-sm text-gray-600">Ketua: {{ $divisi['ketua_divisi'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 sticky top-24">
                    <div class="p-6 bg-gradient-to-r from-gray-800 to-gray-900">
                        <h3 class="text-lg font-bold text-white">📌 Informasi Kegiatan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-semibold text-gray-800">{{ $activity->tanggal_kegiatan->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                        
                        @if($activity->waktu_mulai)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waktu</p>
                                <p class="font-semibold text-gray-800">{{ $activity->waktu_formatted }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($activity->tempat_kegiatan)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tempat</p>
                                <p class="font-semibold text-gray-800">{{ $activity->tempat_kegiatan }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($activity->ketua_pelaksana)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ketua Pelaksana</p>
                                <p class="font-semibold text-gray-800">{{ $activity->ketua_pelaksana }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Jumlah Foto -->
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Dokumentasi</p>
                                <p class="font-semibold text-gray-800">{{ count($activity->photos) }} Foto</p>
                            </div>
                        </div>
                        
                        @if(is_array($activity->divisi) && count($activity->divisi) > 0)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Jumlah Divisi</p>
                                <p class="font-semibold text-gray-800">{{ count(array_filter($activity->divisi, fn($d) => !empty($d['nama_divisi']))) }} Divisi</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Activities -->
        @if($relatedActivities->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                <span class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white">
                    📅
                </span>
                Kegiatan Lainnya
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedActivities as $related)
                    <a href="{{ route('kegiatan.show', $related) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-gray-100">
                        <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 relative overflow-hidden">
                            @if($related->foto_1)
                                <img src="{{ asset('storage/' . $related->foto_1) }}" 
                                     alt="{{ $related->judul_kegiatan }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center w-full h-full">
                                    <span class="text-4xl">📅</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2 mb-1">
                                {{ $related->judul_kegiatan }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $related->tanggal_kegiatan->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition-colors" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full size" class="max-w-full max-h-full object-contain">
</div>

@push('scripts')
<script>
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('[onclick^="changeMainImage"]').forEach(el => {
            el.classList.remove('border-blue-500', 'ring-2', 'ring-blue-300');
            el.classList.add('border-transparent');
        });
        element.classList.remove('border-transparent');
        element.classList.add('border-blue-500', 'ring-2', 'ring-blue-300');
    }
    
    function openLightbox(src) {
        document.getElementById('lightboxImage').src = src;
        document.getElementById('lightbox').classList.remove('hidden');
        document.getElementById('lightbox').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>
@endpush
@endsection
