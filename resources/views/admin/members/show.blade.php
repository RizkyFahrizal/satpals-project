@extends('layouts.admin')

@section('title', 'Detail Anggota - ' . $member->nama_lengkap)

@section('header', 'Detail Anggota')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Anggota
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Photo Section -->
                <div class="relative bg-gradient-to-br from-yellow-400 via-orange-400 to-red-500 p-8 pb-24">
                    <div class="absolute -bottom-16 left-1/2 -translate-x-1/2">
                        <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-4xl font-bold overflow-hidden">
                            @if($member->foto)
                            <img src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->nama_lengkap }}" class="w-full h-full object-cover">
                            @else
                            {{ strtoupper(substr($member->nama_lengkap, 0, 2)) }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pt-20 pb-6 px-6 text-center">
                    <h2 class="text-xl font-bold text-gray-800">{{ $member->nama_lengkap }}</h2>
                    <p class="text-gray-500 text-sm">{{ $member->npm }}</p>

                    <!-- Status Badge -->
                    <div class="mt-3 flex justify-center">
                        @if($member->status === 'aktif')
                        <span class="px-4 py-1.5 bg-green-100 text-green-700 font-semibold rounded-full">
                            ✅ Aktif
                        </span>
                        @elseif($member->status === 'alumni')
                        <span class="px-4 py-1.5 bg-blue-100 text-blue-700 font-semibold rounded-full">
                            🎓 Alumni
                        </span>
                        @else
                        <span class="px-4 py-1.5 bg-red-100 text-red-700 font-semibold rounded-full">
                            ❌ Keluar
                        </span>
                        @endif
                    </div>

                    <!-- Pengurus Badge -->
                    @if($member->isPengurus())
                    <div class="mt-2">
                        <span class="px-4 py-1.5 bg-yellow-100 text-yellow-700 font-semibold rounded-full">
                            ⭐ Pengurus Aktif
                        </span>
                    </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="mt-6 space-y-3">
                        <a href="tel:{{ $member->no_telepon }}" class="flex items-center justify-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $member->no_telepon }}
                        </a>

                        <div class="flex items-center justify-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $member->jenis_kelamin === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('admin.members.edit', $member) }}" 
                           class="flex items-center gap-2 px-5 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Academic Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                    Informasi Akademik
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">NPM</p>
                        <p class="font-semibold text-gray-800 font-mono">{{ $member->npm }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Angkatan</p>
                        <p class="font-semibold text-gray-800">{{ $member->angkatan }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Fakultas</p>
                        <p class="font-semibold text-gray-800">{{ $member->fakultas }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Program Studi</p>
                        <p class="font-semibold text-gray-800">{{ $member->prodi }}</p>
                    </div>
                </div>
            </div>

            <!-- UKM Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                    Informasi Keanggotaan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Tahun Daftar</p>
                        <p class="font-semibold text-gray-800">{{ $member->tahun_daftar }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm text-gray-500 mb-1">Spesifikasi</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @if($member->spesifikasi && count($member->spesifikasi) > 0)
                                @foreach($member->spesifikasi as $spec)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">
                                    @switch($spec)
                                        @case('drum') 🥁 Drum @break
                                        @case('keyboard') 🎹 Keyboard @break
                                        @case('vocal') 🎤 Vocal @break
                                        @case('bass') 🎸 Bass @break
                                        @case('guitar') 🎸 Guitar @break
                                        @default {{ ucfirst($spec) }}
                                    @endswitch
                                </span>
                                @endforeach
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($member->diklatRegistration)
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-sm text-blue-600 mb-1">Asal Pendaftaran</p>
                    <p class="font-semibold text-blue-800">Pendaftaran Diklat #{{ $member->diklatRegistration->id }}</p>
                    <p class="text-sm text-blue-600">{{ $member->diklatRegistration->created_at->format('d M Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Board Position History -->
            @if($member->boardPositions->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Riwayat Kepengurusan
                </h3>

                <div class="space-y-3">
                    @foreach($member->boardPositions as $position)
                    <div class="flex items-center justify-between p-4 rounded-xl {{ $position->is_active ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }}">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ \App\Models\BoardMember::JABATAN_OPTIONS[$position->jabatan] ?? ucfirst($position->jabatan) }}
                            </p>
                            <p class="text-sm text-gray-500">Periode {{ $position->periode }}</p>
                        </div>
                        <div class="text-right">
                            @if($position->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                            @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-semibold rounded-full">Selesai</span>
                            @endif
                            @if($position->user)
                            <p class="text-xs text-gray-500 mt-1">Punya Akun Login</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- User Account Info -->
            @if($member->user)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Akun Login
                </h3>

                <div class="p-4 bg-gray-50 rounded-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <p class="font-semibold text-gray-800">{{ $member->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Role</p>
                            <p class="font-semibold text-gray-800">{{ ucfirst($member->user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timestamps -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Data dibuat: {{ $member->created_at->format('d M Y, H:i') }}
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Terakhir diperbarui: {{ $member->updated_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
