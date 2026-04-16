@extends('layouts.admin')

@section('title', 'Detail Pendaftaran - Admin Satya Palapa')

@section('header', 'Detail Pendaftaran Diklat')

@section('content')
<div class="space-y-6">
    <!-- Back Button & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <a href="{{ route('admin.diklat.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>

        <div class="flex gap-2">
            <!-- Status Badge -->
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                @if($registration->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($registration->status === 'approved') bg-green-100 text-green-800
                @else bg-red-100 text-red-800
                @endif">
                @if($registration->status === 'pending') ⏳ Menunggu Verifikasi
                @elseif($registration->status === 'approved') ✅ Diterima
                @else ❌ Ditolak
                @endif
            </span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Pendaftar - All in One Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Informasi Lengkap Pendaftar</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Data Pribadi Section -->
                    <div class="pb-6 border-b border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-blue-500 rounded"></span>
                            Data Pribadi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                                <p class="font-semibold text-gray-800">{{ $registration->nama_lengkap }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</label>
                                <p class="font-semibold text-gray-800">
                                    {{ $registration->jenis_kelamin === 'laki-laki' ? '👨 Laki-laki' : '👩 Perempuan' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">No. Telepon Pribadi</label>
                                <p class="font-semibold text-gray-800">
                                    <a href="tel:{{ $registration->no_telepon_pribadi }}" class="text-blue-600 hover:underline">
                                        {{ $registration->no_telepon_pribadi }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">No. Telepon Orang Tua</label>
                                <p class="font-semibold text-gray-800">
                                    <a href="tel:{{ $registration->no_telepon_ortu }}" class="text-blue-600 hover:underline">
                                        {{ $registration->no_telepon_ortu ?: '-' }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik Section -->
                    <div class="pb-6 border-b border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-emerald-500 rounded"></span>
                            Data Akademik
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">NPM</label>
                                <p class="font-semibold text-gray-800 font-mono">{{ $registration->npm }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Fakultas</label>
                                <p class="font-semibold text-gray-800">{{ $registration->fakultas }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Program Studi</label>
                                <p class="font-semibold text-gray-800">{{ $registration->prodi }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Tahun Masuk (Angkatan)</label>
                                <p class="font-semibold text-gray-800">{{ $registration->tahun_masuk ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Spesifikasi Musik Section -->
                    <div class="pb-6 border-b border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-purple-500 rounded"></span>
                            Spesifikasi Musik
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($registration->spesifikasi as $spec)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-700">
                                @if($spec === 'drum') 🥁
                                @elseif($spec === 'keyboard') 🎹
                                @elseif($spec === 'vocal') 🎤
                                @elseif($spec === 'bass') 🎸
                                @elseif($spec === 'guitar') 🎸
                                @endif
                                {{ ucfirst($spec) }}
                            </span>
                            @endforeach
                        </div>
                        @if($registration->spesifikasi_lainnya && count($registration->spesifikasi_lainnya) > 0)
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 mb-2">Lainnya:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($registration->spesifikasi_lainnya as $spec)
                                    @if(!empty($spec))
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-700">
                                        🎵 {{ $spec }}
                                    </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Riwayat Kesehatan Section -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                            <span class="w-1 h-4 bg-red-500 rounded"></span>
                            Riwayat Kesehatan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Riwayat Penyakit</label>
                                <div class="bg-gray-50 rounded-lg p-3 mt-1 min-h-[50px]">
                                    <p class="text-sm text-gray-800">{{ $registration->riwayat_penyakit ?: '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 uppercase tracking-wide">Riwayat Alergi</label>
                                <div class="bg-gray-50 rounded-lg p-3 mt-1 min-h-[50px]">
                                    <p class="text-sm text-gray-800">{{ $registration->riwayat_alergi ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Bukti Pembayaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bukti Pembayaran
                    </h3>
                </div>
                <div class="p-4">
                    @if($registration->bukti_pembayaran)
                    <a href="{{ asset('storage/' . $registration->bukti_pembayaran) }}" target="_blank" class="block">
                        <img src="{{ asset('storage/' . $registration->bukti_pembayaran) }}" 
                            alt="Bukti Pembayaran" 
                            class="w-full rounded-xl border border-gray-200 hover:opacity-90 transition-opacity cursor-pointer">
                    </a>
                    <p class="text-xs text-gray-500 mt-2 text-center">Klik gambar untuk melihat ukuran penuh</p>
                    @else
                    <div class="bg-gray-100 rounded-xl p-8 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500">Tidak ada bukti pembayaran</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-900 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Update Status
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <form action="{{ route('admin.diklat.update-status', $registration) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-3">
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="status" value="pending" {{ $registration->status === 'pending' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-gray-200 transition-all duration-200
                                    peer-checked:border-yellow-500 peer-checked:bg-yellow-50
                                    hover:border-yellow-300">
                                    <span class="font-medium text-gray-800">⏳ Menunggu</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="status" value="approved" {{ $registration->status === 'approved' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-gray-200 transition-all duration-200
                                    peer-checked:border-green-500 peer-checked:bg-green-50
                                    hover:border-green-300">
                                    <span class="font-medium text-gray-800">✅ Diterima</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="status" value="rejected" {{ $registration->status === 'rejected' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-3 rounded-xl border-2 border-gray-200 transition-all duration-200
                                    peer-checked:border-red-500 peer-checked:bg-red-50
                                    hover:border-red-300">
                                    <span class="font-medium text-gray-800">❌ Ditolak</span>
                                </div>
                            </label>
                        </div>
                        <button type="submit" class="w-full mt-4 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-xl hover:shadow-lg transition-all">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <h4 class="font-semibold text-gray-800 mb-3">Informasi</h4>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Tanggal Daftar:</span>
                        <span class="font-medium">{{ $registration->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Waktu:</span>
                        <span class="font-medium">{{ $registration->created_at->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Last Update:</span>
                        <span class="font-medium">{{ $registration->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Delete -->
            <form action="{{ route('admin.diklat.destroy', $registration) }}" method="POST" 
                  onsubmit="return confirm('Yakin ingin menghapus data pendaftaran ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-xl border border-red-200 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Data Pendaftaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
