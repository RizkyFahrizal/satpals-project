@extends('layouts.admin')

@section('title', 'Detail Kegiatan')
@section('header', 'Detail Kegiatan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.activities.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.activities.toggle-publish', $activity) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 {{ $activity->is_published ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} font-medium rounded-lg transition-colors flex items-center gap-2">
                    @if($activity->is_published)
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
            <a href="{{ route('admin.activities.edit', $activity) }}" class="px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Photo Gallery -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if(count($activity->photos) > 0)
                    <div class="aspect-video bg-gray-100" id="mainPhotoContainer">
                        <img src="{{ asset('storage/' . $activity->photos[0]) }}" 
                             alt="{{ $activity->judul_kegiatan }}" 
                             id="mainPhoto"
                             class="w-full h-full object-cover">
                    </div>
                    @if(count($activity->photos) > 1)
                    <div class="p-4 flex gap-3">
                        @foreach($activity->photos as $index => $photo)
                        <button onclick="changeMainPhoto('{{ asset('storage/' . $photo) }}')" 
                                class="w-24 h-16 rounded-lg overflow-hidden border-2 hover:border-blue-500 transition-colors {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }}">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Foto {{ $index + 1 }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                @else
                    <div class="aspect-video bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center">
                        <span class="text-8xl">📅</span>
                    </div>
                @endif
            </div>

            <!-- Title & Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $activity->judul_kegiatan }}</h1>
                    @if($activity->is_published)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">Dipublikasikan</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">Draft</span>
                    @endif
                </div>

                @if($activity->tujuan_kegiatan)
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Tujuan Kegiatan</h3>
                    <p class="text-gray-700">{{ $activity->tujuan_kegiatan }}</p>
                </div>
                @endif

                @if($activity->deskripsi)
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi</h3>
                    <div class="text-gray-700 whitespace-pre-line">{{ $activity->deskripsi }}</div>
                </div>
                @endif
            </div>

            <!-- Kepanitiaan -->
            @if($activity->ketua_pelaksana || ($activity->divisi && count($activity->divisi) > 0))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Kepanitiaan
                </h3>

                @if($activity->ketua_pelaksana)
                <div class="mb-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg">
                    <p class="text-sm text-gray-500">Ketua Pelaksana</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $activity->ketua_pelaksana }}</p>
                </div>
                @endif

                @if($activity->divisi && count($activity->divisi) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($activity->divisi as $divisi)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">{{ $divisi['nama_divisi'] ?? 'Divisi' }}</p>
                        <p class="font-semibold text-gray-800">{{ $divisi['ketua_divisi'] ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-4 border-b">Informasi Kegiatan</h3>

                <div class="space-y-4">
                    <!-- Tanggal -->
                    <div class="flex items-start gap-4">
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

                    <!-- Waktu -->
                    @if($activity->waktu_mulai)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu</p>
                            <p class="font-semibold text-gray-800">{{ $activity->waktu_formatted }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Tempat -->
                    @if($activity->tempat_kegiatan)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Created At -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ditambahkan</p>
                            <p class="font-semibold text-gray-800">{{ $activity->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Delete Button -->
                <div class="mt-6 pt-4 border-t">
                    <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" 
                        onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-600 font-medium rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Kegiatan
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
        document.querySelectorAll('#mainPhotoContainer + div button').forEach(btn => {
            btn.classList.remove('border-blue-500');
            btn.classList.add('border-transparent');
        });
        event.currentTarget.classList.remove('border-transparent');
        event.currentTarget.classList.add('border-blue-500');
    }
</script>
@endsection
