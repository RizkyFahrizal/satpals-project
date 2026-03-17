@extends('layouts.admin')

@section('title', 'Detail Prestasi - ' . $achievement->judul_lomba)

@section('header', 'Detail Prestasi')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.achievements.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Prestasi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Photo Gallery -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if(count($achievement->photos) > 0)
                <div class="grid grid-cols-1 gap-2">
                    <!-- Main Photo -->
                    <div class="relative aspect-video bg-gray-100">
                        <img src="{{ asset('storage/' . $achievement->photos[0]) }}" 
                             alt="{{ $achievement->judul_lomba }}" 
                             class="w-full h-full object-cover"
                             id="mainPhoto">
                        
                        <!-- Badge Juara -->
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-2 bg-gradient-to-r from-amber-400 to-yellow-500 text-white text-lg font-bold rounded-full shadow-lg">
                                🏆 {{ $achievement->juara_label }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="absolute top-4 right-4">
                            @if($achievement->is_published)
                            <span class="px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full shadow">✓ Dipublikasi</span>
                            @else
                            <span class="px-3 py-1 bg-gray-500 text-white text-sm font-semibold rounded-full shadow">Draft</span>
                            @endif
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    @if(count($achievement->photos) > 1)
                    <div class="flex gap-2 p-4">
                        @foreach($achievement->photos as $index => $photo)
                        <button onclick="changeMainPhoto('{{ asset('storage/' . $photo) }}')" 
                                class="w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-yellow-400 transition-all focus:border-yellow-400 focus:outline-none">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto {{ $index + 1 }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <div class="text-center text-gray-400">
                        <svg class="w-20 h-20 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p>Belum ada foto</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $achievement->judul_lomba }}</h2>
                
                @if($achievement->deskripsi)
                <div class="prose prose-gray max-w-none">
                    <p class="text-gray-600 whitespace-pre-line">{{ $achievement->deskripsi }}</p>
                </div>
                @else
                <p class="text-gray-400 italic">Belum ada deskripsi</p>
                @endif
            </div>

            <!-- Band & Members -->
            @if($achievement->nama_band || ($achievement->anggota && count($achievement->anggota) > 0))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">🎸</span> Band & Anggota
                </h3>

                @if($achievement->nama_band)
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Nama Band</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $achievement->nama_band }}</p>
                </div>
                @endif

                @if($achievement->anggota && count($achievement->anggota) > 0)
                <div>
                    <p class="text-sm text-gray-500 mb-2">Anggota</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($achievement->anggota as $anggota)
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            👤 {{ $anggota }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Lomba</h3>
                
                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal</p>
                            <p class="font-semibold text-gray-800">{{ $achievement->tanggal_lomba->format('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Tempat -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tempat</p>
                            <p class="font-semibold text-gray-800">{{ $achievement->tempat_lomba }}</p>
                        </div>
                    </div>

                    <!-- Penyelenggara -->
                    @if($achievement->penyelenggara)
                    <div class="flex items-start gap-3">
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

                    <!-- Juara -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-xl">🏆</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Penghargaan</p>
                            <p class="font-bold text-amber-600 text-lg">{{ $achievement->juara_label }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Dibuat: {{ $achievement->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Diperbarui: {{ $achievement->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.achievements.edit', $achievement) }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Prestasi
                    </a>
                    
                    <form action="{{ route('admin.achievements.toggle-publish', $achievement) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 {{ $achievement->is_published ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-semibold rounded-xl transition-all">
                            @if($achievement->is_published)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                            Sembunyikan
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Publikasikan
                            @endif
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.achievements.destroy', $achievement) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus prestasi ini? Aksi ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Prestasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMainPhoto(src) {
    document.getElementById('mainPhoto').src = src;
}
</script>
@endsection
