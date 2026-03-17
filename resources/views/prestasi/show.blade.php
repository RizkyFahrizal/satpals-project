@extends('layouts.app')

@section('title', $achievement->judul_lomba . ' - Prestasi Satya Palapa')

@section('content')
<!-- Hero/Header -->
<div class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-12">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-32 h-32 bg-yellow-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 bg-orange-400/10 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-yellow-400 transition-colors">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('prestasi.index') }}" class="hover:text-yellow-400 transition-colors">Galeri Prestasi</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-yellow-400">Detail Prestasi</span>
        </nav>
        
        <!-- Badge -->
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
        <span class="inline-block px-4 py-2 {{ $badgeClass }} text-sm font-bold rounded-full mb-4">
            🏆 {{ $achievement->juara_label }}
        </span>
        
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $achievement->judul_lomba }}</h1>
        
        @if($achievement->nama_band)
            <div class="flex items-center gap-2 text-gray-300 text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-semibold">{{ $achievement->nama_band }}</span>
            </div>
        @endif
    </div>
</div>

<!-- Main Content -->
<div class="bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Photos -->
            <div class="lg:col-span-2">
                <!-- Main Photo Gallery -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    @if(count($achievement->photos) > 0)
                        <!-- Main Photo -->
                        <div class="aspect-video bg-gray-100 relative" id="mainPhotoContainer">
                            <img src="{{ asset('storage/' . $achievement->photos[0]) }}" 
                                 alt="{{ $achievement->judul_lomba }}" 
                                 id="mainPhoto"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Photo Thumbnails -->
                        @if(count($achievement->photos) > 1)
                            <div class="p-4 flex gap-3">
                                @foreach($achievement->photos as $index => $photo)
                                    <button onclick="changeMainPhoto('{{ asset('storage/' . $photo) }}')" 
                                            class="w-24 h-16 rounded-lg overflow-hidden border-2 hover:border-yellow-400 transition-colors {{ $index === 0 ? 'border-yellow-400' : 'border-transparent' }}">
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             alt="Foto {{ $index + 1 }}" 
                                             class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="aspect-video bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center">
                            <span class="text-8xl">🏆</span>
                        </div>
                    @endif
                </div>
                
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-xl p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Deskripsi
                    </h2>
                    <div class="prose prose-gray max-w-none">
                        {!! nl2br(e($achievement->deskripsi ?: 'Tidak ada deskripsi.')) !!}
                    </div>
                </div>
                
                <!-- Anggota -->
                @if($achievement->anggota && count($achievement->anggota) > 0)
                    <div class="bg-white rounded-2xl shadow-xl p-6 mt-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Anggota Tim
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($achievement->anggota as $anggota)
                                <span class="px-4 py-2 bg-gradient-to-r from-yellow-100 to-orange-100 text-gray-800 rounded-full font-medium">
                                    {{ $anggota }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Right Column - Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-4 border-b">Informasi Lomba</h3>
                    
                    <!-- Info Items -->
                    <div class="space-y-4">
                        <!-- Tanggal -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Lomba</p>
                                <p class="font-semibold text-gray-800">{{ $achievement->tanggal_lomba->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                        
                        <!-- Tempat -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tempat</p>
                                <p class="font-semibold text-gray-800">{{ $achievement->tempat_lomba ?: '-' }}</p>
                            </div>
                        </div>
                        
                        <!-- Penyelenggara -->
                        @if($achievement->penyelenggara)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Penyelenggara</p>
                                    <p class="font-semibold text-gray-800">{{ $achievement->penyelenggara }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Prestasi/Juara -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Prestasi</p>
                                <p class="font-semibold text-gray-800">{{ $achievement->juara_label }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back Button -->
                    <div class="mt-8 pt-4 border-t">
                        <a href="{{ route('prestasi.index') }}" 
                           class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:shadow-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Galeri
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Achievements -->
        @if($relatedAchievements->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Prestasi Lainnya</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedAchievements as $related)
                        <a href="{{ route('prestasi.show', $related) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-gray-100">
                            <div class="aspect-video bg-gradient-to-br from-yellow-100 to-orange-100 relative overflow-hidden">
                                @if($related->foto_1)
                                    <img src="{{ asset('storage/' . $related->foto_1) }}" 
                                         alt="{{ $related->judul_lomba }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center w-full h-full">
                                        <span class="text-4xl">🏆</span>
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2">
                                    @php
                                        $relatedBadgeClass = match($related->juara) {
                                            'juara_1' => 'bg-yellow-400 text-yellow-900',
                                            'juara_2' => 'bg-gray-300 text-gray-800',
                                            'juara_3' => 'bg-orange-400 text-orange-900',
                                            default => 'bg-gray-600 text-white'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 {{ $relatedBadgeClass }} text-xs font-bold rounded-full">
                                        {{ $related->juara_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-2">
                                    {{ $related->judul_lomba }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $related->tanggal_lomba->format('Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function changeMainPhoto(src) {
        document.getElementById('mainPhoto').src = src;
        // Update thumbnail borders
        document.querySelectorAll('#mainPhotoContainer + div button').forEach(btn => {
            btn.classList.remove('border-yellow-400');
            btn.classList.add('border-transparent');
        });
        event.currentTarget.classList.remove('border-transparent');
        event.currentTarget.classList.add('border-yellow-400');
    }
</script>
@endsection
